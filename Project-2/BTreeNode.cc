#include "BTreeNode.h"
#include <cstring>
#include <iostream>


using namespace std;

// get # records stored in the page
static int getNodeKeyCount(const char* page);

// update # records stored in the page
static void setNodeKeyCount(char* page, int count);

// get next ptr
static int getNextPtr(const char* page);

// update next ptr
static void setNextPtr(char* page, PageId pid);

/*
 * Read the content of the node from the page pid in the PageFile pf.
 * @param pid[IN] the PageId to read
 * @param pf[IN] PageFile to read from
 * @return 0 if successful. Return an error code if there is an error.
 */
BTLeafNode::BTLeafNode() {
	memset(buffer, 0, PageFile::PAGE_SIZE);
}

RC BTLeafNode::read(PageId pid, const PageFile& pf)
{ 
	return pf.read(pid, buffer);
}
    
/*
 * Write the content of the node to the page pid in the PageFile pf.
 * @param pid[IN] the PageId to write to
 * @param pf[IN] PageFile to write to
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTLeafNode::write(PageId pid, PageFile& pf)
{ 
	return pf.write(pid, buffer);
}

RC BTLeafNode::write(char* ptr, int size)
{
	memcpy(buffer+sizeof(int), ptr, (sizeof(int) + sizeof(RecordId)) * size);
	setNodeKeyCount(buffer, size);
	return 0;
}

/*
 * Return the number of keys stored in the node.
 * @return the number of keys in the node
 */
int BTLeafNode::getKeyCount()
{
	return getNodeKeyCount(buffer);
}

/*
 * Insert a (key, rid) pair to the node.
 * @param key[IN] the key to insert
 * @param rid[IN] the RecordId to insert
 * @return 0 if successful. Return an error code if the node is full.
 */
RC BTLeafNode::insert(int key, const RecordId& rid)
{

	if (BTLeafNode::getKeyCount() >= BTLeafNode::MAX_KEY_NUM) return RC_NODE_FULL;
	return BTLeafNode::insertToNode(key, rid);
}

RC BTLeafNode::insertToNode(int key, const RecordId& rid)
{
	int total = BTLeafNode::getKeyCount();

	if (total == 0) {
		char *ptr = BTLeafNode::slotPtr(0);
		memcpy(ptr, &key, sizeof(int));
		memcpy(ptr+sizeof(int), &rid, sizeof(RecordId));
		setNodeKeyCount(buffer, total + 1);
		return 0;
	}

	int begin = 0, end = total - 1, k;

	while (begin < end) {
		int mid = begin + (end - begin) / 2;
		char *ptr = BTLeafNode::slotPtr(mid);
		memcpy(&k, ptr, sizeof(int));
		if (key == k) return RC_CONFLICT_KEY;

		if (key > k) {
			begin = mid+1;
		} else {
			end = mid-1;
		}
	}

	char *ptr = BTLeafNode::slotPtr(begin);
	memcpy(&k, ptr, sizeof(int));

	if (key == k) return RC_CONFLICT_KEY;

	if (key < k) {
		BTLeafNode::insertToSlot(key, rid, ptr, total - begin);
	} else {
		BTLeafNode::insertToSlot(key, rid, ptr + sizeof(int) + sizeof(RecordId),
		                         total - begin - 1);
	}
	setNodeKeyCount(buffer, total + 1);
	return 0;
}

RC BTLeafNode::insertToSlot(int key, const RecordId& rid, char* ptr, int length)
{
	char temp[PageFile::PAGE_SIZE];

	memcpy(temp, ptr, (sizeof(int) + sizeof(RecordId)) * length);
	memcpy(ptr+(sizeof(int) + sizeof(RecordId)), temp, 
			   (sizeof(int) + sizeof(RecordId)) * length);

	memcpy(ptr, &key, sizeof(int));
	memcpy(ptr+sizeof(int), &rid, sizeof(RecordId));
	return 0;
}

/*
 * Insert the (key, rid) pair to the node
 * and split the node half and half with sibling.
 * The first key of the sibling node is returned in siblingKey.
 * @param key[IN] the key to insert.
 * @param rid[IN] the RecordId to insert.
 * @param sibling[IN] the sibling node to split with. This node MUST be EMPTY when this function is called.
 * @param siblingKey[OUT] the first key in the sibling node after split.
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTLeafNode::insertAndSplit(int key, const RecordId& rid, 
                              BTLeafNode& sibling, int& siblingKey)
{
	BTLeafNode::insertToNode(key, rid);

	int count = getNodeKeyCount(buffer);
	if (count <= 1 || sibling.getKeyCount() > 0) return RC_INVALID_DIVIDE;

	char *ptr = BTLeafNode::slotPtr(count/2);
	memcpy(&siblingKey, ptr, sizeof(int));

	sibling.write(ptr, count-count/2);
	setNodeKeyCount(buffer, count/2);
	return 0;
}

/**
 * If searchKey exists in the node, set eid to the index entry
 * with searchKey and return 0. If not, set eid to the index entry
 * immediately after the largest index key that is smaller than searchKey,
 * and return the error code RC_NO_SUCH_RECORD.
 * Remember that keys inside a B+tree node are always kept sorted.
 * @param searchKey[IN] the key to search for.
 * @param eid[OUT] the index entry number with searchKey or immediately
                   behind the largest key smaller than searchKey.
 * @return 0 if searchKey is found. Otherwise return an error code.
 */
RC BTLeafNode::locate(int searchKey, int& eid)
{ 
	if (getNodeKeyCount(buffer) <= 0) return RC_NO_SUCH_RECORD;
	return BTLeafNode::binaryLocate(searchKey, eid, 0, getNodeKeyCount(buffer)-1); 
}

RC BTLeafNode::binaryLocate(int searchKey, int& eid, int begin, int end)
{
	int key;
	if (begin > end) {
		return RC_NO_SUCH_RECORD;
	}

	int mid = begin + (end-begin)/2;
	char *ptr = BTLeafNode::slotPtr(mid);
	memcpy(&key, ptr, sizeof(int));

	if (key == searchKey) {
		eid = mid;
		return 0;
	}

	if (key < searchKey) {
		return BTLeafNode::binaryLocate(searchKey, eid, mid+1, end);
	} else {
		return BTLeafNode::binaryLocate(searchKey, eid, begin, mid-1);
	}
}

/*
 * Read the (key, rid) pair from the eid entry.
 * @param eid[IN] the entry number to read the (key, rid) pair from
 * @param key[OUT] the key from the entry
 * @param rid[OUT] the RecordId from the entry
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTLeafNode::readEntry(int eid, int& key, RecordId& rid)
{
	if (eid < 0 || eid >= BTLeafNode::getKeyCount()) return RC_INVALID_EID;

	char *ptr = BTLeafNode::slotPtr(eid);

	memcpy(&key, ptr, sizeof(int));
	memcpy(&rid, ptr+sizeof(int), sizeof(RecordId));

	return 0;
}

char* BTLeafNode::slotPtr(int n)
{
	return (buffer+sizeof(int)) + (sizeof(int)+sizeof(RecordId))*n;
}

/*
 * Return the pid of the next slibling node.
 * @return the PageId of the next sibling node 
 */
PageId BTLeafNode::getNextNodePtr()
{ 
	return getNextPtr(buffer); 
}

/*
 * Set the pid of the next slibling node.
 * @param pid[IN] the PageId of the next sibling node 
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTLeafNode::setNextNodePtr(PageId pid)
{
	setNextPtr(buffer, pid);
	return 0;
}

BTNonLeafNode::BTNonLeafNode() {
	memset(buffer, 0, PageFile::PAGE_SIZE);
}

/*
 * Read the content of the node from the page pid in the PageFile pf.
 * @param pid[IN] the PageId to read
 * @param pf[IN] PageFile to read from
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTNonLeafNode::read(PageId pid, const PageFile& pf)
{ 
	return pf.read(pid, buffer);
}
    
/*
 * Write the content of the node to the page pid in the PageFile pf.
 * @param pid[IN] the PageId to write to
 * @param pf[IN] PageFile to write to
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTNonLeafNode::write(PageId pid, PageFile& pf)
{
	return pf.write(pid,buffer);
}

RC BTNonLeafNode::write(char* ptr, int size)
{
	memcpy(buffer+sizeof(int), ptr, sizeof(PageId) + (sizeof(int) + sizeof(PageId)) * size);
	setNodeKeyCount(buffer, size);
	return 0;
}
/*
 * Return the number of keys stored in the node.
 * @return the number of keys in the node
 */
int BTNonLeafNode::getKeyCount()
{
  return getNodeKeyCount(buffer);
}


/*
 * Insert a (key, pid) pair to the node.
 * @param key[IN] the key to insert
 * @param pid[IN] the PageId to insert
 * @return 0 if successful. Return an error code if the node is full.
 */
RC BTNonLeafNode::insert(int key, PageId pid)
{
	if (BTNonLeafNode::getKeyCount() >= BTNonLeafNode::MAX_KEY_NUM) return RC_NODE_FULL;

	return BTNonLeafNode::insertToNode(key, pid);
}

RC BTNonLeafNode::insertToNode(int key, PageId pid)
{
	int total = BTNonLeafNode::getKeyCount();

	if (total == 0) {
		char *ptr = BTNonLeafNode::slotPtr(0);
		memcpy(ptr, &key, sizeof(int));
		memcpy(ptr+sizeof(int), &pid, sizeof(PageId));
		setNodeKeyCount(buffer, total + 1);
		return 0;
	}

	int begin = 0, end = total - 1, k;

	while (begin < end) {
		int mid = begin + (end - begin) / 2;
		char *ptr = BTNonLeafNode::slotPtr(mid);
		memcpy(&k, ptr, sizeof(int));
		if (key == k) return RC_CONFLICT_KEY;

		if (key > k) {
			begin = mid+1;
		} else {
			end = mid-1;
		}
	}

	char *ptr = BTNonLeafNode::slotPtr(begin);
	memcpy(&k, ptr, sizeof(int));

	if (key == k) return RC_CONFLICT_KEY;

	if (key < k) {
		BTNonLeafNode::insertToSlot(key, pid, ptr, total - begin);
	} else {
		BTNonLeafNode::insertToSlot(key, pid, ptr + sizeof(int) + sizeof(PageId),
		                         total - begin - 1);
	}
	setNodeKeyCount(buffer, total + 1);
	return 0;
}

RC BTNonLeafNode::insertToSlot(int key, const PageId pid, char* ptr, int length)
{
	char temp[PageFile::PAGE_SIZE];

	memcpy(temp, ptr, (sizeof(int) + sizeof(PageId)) * length);
	memcpy(ptr+(sizeof(int) + sizeof(PageId)), temp, 
			   (sizeof(int) + sizeof(PageId)) * length);

	memcpy(ptr, &key, sizeof(int));
	memcpy(ptr+sizeof(int), &pid, sizeof(PageId));
	return 0;
}

char* BTNonLeafNode::slotPtr(int n)
{
	return (buffer+sizeof(int)*2) + (sizeof(int)+sizeof(PageId))*n;
}
/*
 * Insert the (key, pid) pair to the node
 * and split the node half and half with sibling.
 * The middle key after the split is returned in midKey.
 * @param key[IN] the key to insert
 * @param pid[IN] the PageId to insert
 * @param sibling[IN] the sibling node to split with. This node MUST be empty when this function is called.
 * @param midKey[OUT] the key in the middle after the split. This key should be inserted to the parent node.
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTNonLeafNode::insertAndSplit(int key, PageId pid, BTNonLeafNode& sibling, int& midKey)
{
	BTNonLeafNode::insertToNode(key, pid);

	int count = getNodeKeyCount(buffer);
	if (count <= 1 || sibling.getKeyCount() > 0) return RC_INVALID_DIVIDE;

	char *ptr = BTNonLeafNode::slotPtr(count/2);
	memcpy(&midKey, ptr, sizeof(int));

	sibling.write(ptr - sizeof(int), count - count/2);
	setNodeKeyCount(buffer, count/2);
	return 0;
}

/*
 * Given the searchKey, find the child-node pointer to follow and
 * output it in pid.
 * @param searchKey[IN] the searchKey that is being looked up.
 * @param pid[OUT] the pointer to the child node to follow.
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTNonLeafNode::locateChildPtr(int searchKey, PageId& pid)
{
	int total = getNodeKeyCount(buffer);

	if (total <= 0) return RC_NO_SUCH_RECORD;
	int begin = 0, end = total - 1, k;

	while (begin < end) {
		int mid = begin + (end - begin) / 2;
		char *ptr = BTNonLeafNode::slotPtr(mid);
		memcpy(&k, ptr, sizeof(int));
		if (searchKey == k) {
			memcpy(&pid, ptr+sizeof(int), sizeof(PageId));
			return 0;
		}
		if (searchKey > k) {
			begin = mid+1;
		} else {
			end = mid-1;
		}
	}

	char *ptr = BTNonLeafNode::slotPtr(begin);
	memcpy(&k, ptr, sizeof(int));

	if (searchKey < k) {
		memcpy(&pid, ptr-sizeof(PageId), sizeof(PageId));
		return 0;
	} else {
		memcpy(&pid, ptr+sizeof(int), sizeof(PageId));
		return 0;
	} 
}

/*
 * Initialize the root node with (pid1, key, pid2).
 * @param pid1[IN] the first PageId to insert
 * @param key[IN] the key that should be inserted between the two PageIds
 * @param pid2[IN] the PageId to insert behind the key
 * @return 0 if successful. Return an error code if there is an error.
 */
RC BTNonLeafNode::initializeRoot(PageId pid1, int key, PageId pid2)
{
	if (getNodeKeyCount(buffer) > 0) return RC_END_OF_TREE;

	memcpy(buffer+sizeof(int), &pid1, sizeof(int));
	BTNonLeafNode::insert(key, pid2);
}

static int getNodeKeyCount(const char* page)
{
  int count;

  // the first four bytes of a page contains # records in the page
  memcpy(&count, page, sizeof(int));
  return count;
}

static void setNodeKeyCount(char* page, int count)
{
  // the first four bytes of a page contains # records in the page
  memcpy(page, &count, sizeof(int));
}

static int getNextPtr(const char* page)
{
  int pid;

  // the first four bytes of a page contains # records in the page
  memcpy(&pid, page+PageFile::PAGE_SIZE-sizeof(int), sizeof(int));
  return pid;
}

static void setNextPtr(char* page, PageId pid)
{
  // the first four bytes of a page contains # records in the page
  memcpy(page+PageFile::PAGE_SIZE-sizeof(int), &pid, sizeof(int));
}