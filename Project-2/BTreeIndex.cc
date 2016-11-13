/*
 * Copyright (C) 2008 by The Regents of the University of California
 * Redistribution of this file is permitted under the terms of the GNU
 * Public License (GPL).
 *
 * @author Junghoo "John" Cho <cho AT cs.ucla.edu>
 * @date 3/24/2008
 */
 
#include "BTreeIndex.h"
#include "BTreeNode.h"

using namespace std;

/*
 * BTreeIndex constructor
 */
BTreeIndex::BTreeIndex()
{
    rootPid = -1;
}

/*
 * Open the index file in read or write mode.
 * Under 'w' mode, the index file should be created if it does not exist.
 * @param indexname[IN] the name of the index file
 * @param mode[IN] 'r' for read, 'w' for write
 * @return error code. 0 if no error
 */
RC BTreeIndex::open(const string& indexname, char mode)
{
	char buffer[PageFile::PAGE_SIZE]; 
	//fail to open
	if (pf.open(indexname, mode) < 0) 
		return pf.open(indexname, mode);

	if (pf.endPid() == 0) {
		rootPid = -1;
		treeHeight = 0;
		close();
		return open(indexname, mode);
	}
	else {
		//fail to read
		if (pf.read(0, buffer) < 0) return pf.read(0, buffer);

		memcpy(&rootPid, buffer, sizeof(PageId));
		memcpy(&treeHeight, buffer + sizeof(PageId), sizeof(int));
	}

	return 0;
}

/*
 * Close the index file.
 * @return error code. 0 if no error
 */
RC BTreeIndex::close()
{
	char buffer[PageFile::PAGE_SIZE]; 
	memcpy(buffer, &rootPid, sizeof(PageId));
	memcpy(buffer + sizeof(PageId), &treeHeight, sizeof(int));
	if (pf.write(0, buffer) < 0) 
		return pf.write(0, buffer);
	return pf.close(); 
}

/*
 * Insert (key, RecordId) pair to the index.
 * @param key[IN] the key for the value inserted into the index
 * @param rid[IN] the RecordId for the record being inserted into the index
 * @return error code. 0 if no error
 */
RC BTreeIndex::insert(int key, const RecordId& rid)
{
	if (treeHeight == 0) { 
		BTLeafNode leafnode = BTLeafNode();
		leafnode.insert(key, rid);
		rootPid = pf.endPid();
		leafnode.write(rootPid, pf);
		treeHeight++; 
		return 0;
	}
	else {
		int ikey;
		PageId ipid;
		int result = helper(key, rid, 1, rootPid, ikey, ipid);
		//if the inserted node is to split first 
		if (result == 1) { 
			BTNonLeafNode parentnode = BTNonLeafNode();
			parentnode.initializeRoot(rootPid, ikey, ipid);
			rootPid = pf.endPid();
			parentnode.write(rootPid, pf);
			treeHeight++;
			return 0;
		}
		else if (result == 0) 
			return 0;
		else
			return -1;
	}

}

/**
 * help function
 */
RC BTreeIndex::helper(int& key, const RecordId& rid, int height, PageId currentPid, int& ikey, PageId& ipid) {
	//If it is already at the leaf level
	if (height == treeHeight) { 
		BTLeafNode leafnode = BTLeafNode();
		leafnode.read(currentPid, pf);
		//There is enough space for this insertion in the leafnode
		if (leafnode.getKeyCount() < MAX_KEY_NUM -1) { //Max. number of keys for leaf nodes	
			leafnode.insert(key, rid);
			leafnode.write(currentPid, pf);
			return 0;
		}
		else {
			BTLeafNode siblingnode = BTLeafNode();

			leafnode.insertAndSplit(key, rid, siblingnode, ikey); 
			ipid = pf.endPid();
			siblingnode.setNextNodePtr(leafnode.getNextNodePtr());
			leafnode.setNextNodePtr(ipid);
			leafnode.write(currentPid, pf);
			siblingnode.write(ipid, pf);
			return 1;
		}

	}
	else { //NonLeaf level
		BTNonLeafNode nonleafnode = BTNonLeafNode();
		nonleafnode.read(currentPid, pf);
		PageId childpid;
		nonleafnode.locateChildPtr(key, childpid);

		int result = helper(key, rid, height + 1, childpid, ikey, ipid);
		if (result < 0)
			return -1;
		else if (result == 1) { //if overflow
			//enough space for this node
			if (nonleafnode.getKeyCount() < MAX_KEY_NUM - 1) {
				nonleafnode.insert(ikey, ipid);
				nonleafnode.write(currentPid, pf);
				ikey = -1;
				return 0;
			}
			else { //no room in this one
				BTNonLeafNode siblingnode = BTNonLeafNode();
				int siblingKey;

				nonleafnode.insertAndSplit(ikey, ipid, siblingnode, siblingKey); //insert and split
				ikey = siblingKey;
				ipid = pf.endPid();
				siblingnode.write(ipid, pf);
				nonleafnode.write(currentPid, pf);
				return 1;
			}
		}
		else
			return 0;
	}

}

/**
 * Run the standard B+Tree key search algorithm and identify the
 * leaf node where searchKey may exist. If an index entry with
 * searchKey exists in the leaf node, set IndexCursor to its location
 * (i.e., IndexCursor.pid = PageId of the leaf node, and
 * IndexCursor.eid = the searchKey index entry number.) and return 0.
 * If not, set IndexCursor.pid = PageId of the leaf node and
 * IndexCursor.eid = the index entry immediately after the largest
 * index key that is smaller than searchKey, and return the error
 * code RC_NO_SUCH_RECORD.
 * Using the returned "IndexCursor", you will have to call readForward()
 * to retrieve the actual (key, rid) pair from the index.
 * @param key[IN] the key to find
 * @param cursor[OUT] the cursor pointing to the index entry with
 *                    searchKey or immediately behind the largest key
 *                    smaller than searchKey.
 * @return 0 if searchKey is found. Othewise an error code
 */
RC BTreeIndex::locate(int searchKey, IndexCursor& cursor)
{
	//implemented by ACJ 2016/11/12
	BTLeafNode leafnode = BTLeafNode();
	BTNonLeafNode nonleafnode = BTNonLeafNode();

	//Initialize
	PageId pid = rootPid;
	int eid = -1;

	//NonLeafNode Search
	if (treeHeight > 1) { 
		for (int i = 0; i < treeHeight - 1; i++) { 
			if (nonleafnode.read(pid, pf) < 0) 
				return EC;
			if (nonleafnode.locateChildPtr(searchKey, pid) < 0)
				return EC;
		}
	}

	//LeafNode Search
	if (leafnode.read(pid, pf) < 0)
		return EC;
	if (leafnode.locate(searchKey, eid) < 0) 
		return EC;
	else { 
		cursor.pid = pid;
		cursor.eid = eid;
	}
	return 0;
}

/*
 * Read the (key, rid) pair at the location specified by the index cursor,
 * and move foward the cursor to the next entry.
 * @param cursor[IN/OUT] the cursor pointing to an leaf-node index entry in the b+tree
 * @param key[OUT] the key stored at the index cursor location.
 * @param rid[OUT] the RecordId stored at the index cursor location.
 * @return error code. 0 if no error
 */
RC BTreeIndex::readForward(IndexCursor& cursor, int& key, RecordId& rid)
{
	//implemented by ACJ 2016/11/12
	RC rc = 0;

	PageId currentPid = cursor.pid;
	int currentEid = cursor.eid;

	BTLeafNode currentNode;
	rc = currentNode.read(cursor.pid, pf);
	if (rc != 0) {
		return rc; // RC_FILE_READ_FAILED;
	}

	rc = currentNode.readEntry(currentEid, key, rid);
	if (rc != 0) {
		return rc; // RC_INVALID_CURSOR;
	}

	// If there is an overflow
	if (currentEid == currentNode.getKeyCount() - 1) { 
		//move foward the cursor to the next entry, this case the next node
		cursor.eid = 0;
		cursor.pid = currentNode.getNextNodePtr();
	}
	else { // there is no overflow issue
		cursor.eid = ++currentEid;
		cursor.pid = currentPid;
	}

	return rc;
}
