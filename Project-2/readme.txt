This is the lastest version of Project 2. 

In Project 2B, we implemented the BTreeNode including TreeNode and NonTreeNode. Inside each node we used binary search method to locate the target value.

In Project 2C, we finally completed the implementation of BTree. In BTreeIndex part, we provided with three important methods as required. Also, there is a helper method for the insertion.

insert() method provides a way to insert (key, RecordId) pair to the index.

locate() method run the standard B+Tree key search algorithm and identify the
leaf node where searchKey may exist. 

By implementing readForward(), we read the (key, rid) pair at the location specified by the index cursor, and move foward the cursor to the next entry.

In Project 2D, we modified load() and select() after the implementation of Btree. The load() method create the index for the data, and select() performs as an interface for users to select in Bruinbase.

#
#
#
#