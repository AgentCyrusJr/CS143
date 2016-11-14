/*
 * Copyright (C) 2008 by The Regents of the University of California
 * Redistribution of this file is permitted under the terms of the GNU
 * Public License (GPL).
 *
 * @author Junghoo "John" Cho <cho AT cs.ucla.edu>
 * @date 3/24/2008
 */

#include "UnitTest.h"
#include <cstdio>
#include <vector>
#include <cassert>
#include <cstring>
#include <fstream>
#include <stdlib.h>
#include <time.h>

using namespace std;

void BTLeafNodeTest::printNode()
{
  int key;
  for (int i = 0; i < getKeyCount(); i++) {
    char *ptr = slotPtr(i);
    memcpy(&key, ptr, sizeof(int));
    std::cout<<" "<<key;
  }
  std::cout<<std::endl;
};

void BTNonLeafNodeTest::printNode()
{
  int key,pageId;
  if (getKeyCount()>0) 
  {
    char *ptr = slotPtr(0);
    memcpy(&pageId, ptr - sizeof(PageId), sizeof(PageId));
    std::cout<<" "<<pageId;
  }
  for (int i = 0; i < getKeyCount(); i++) 
  {
    char *ptr = slotPtr(i);
    memcpy(&key, ptr, sizeof(int));
    std::cout<<" *"<<key<<"*";
    memcpy(&pageId, ptr + sizeof(int), sizeof(PageId));
    std::cout<<" "<<pageId;

  }
  std::cout<<std::endl;
};

int main()
{
  string testIndex = "test.tbl";
  BTreeIndex btree;
  std::remove(testIndex.c_str());
  //init test
  btree.open("test.tbl", 'w');
  assert(btree.getRootPid()==-1);
  assert(btree.getHeight()==0);
  assert(btree.getPageFile().endPid()==1);

  char buffer[PageFile::PAGE_SIZE]; 
  RC rc;
  int rp,th;
  assert((rc = btree.getPageFile().read(0, buffer)) >= 0);
  memcpy(&rp, buffer, sizeof(PageId));
  memcpy(&th, buffer + sizeof(PageId), sizeof(int));
  assert(btree.getHeight()==th);
  assert(btree.getPageFile().endPid()==1);
  //remove(testIndex.c_str());

  //test insert root

  int keyArray[MAX_TUPLE];
  srand (time(NULL));
  for (int i = 1; i < MAX_TUPLE+1; i++) 
  {
      keyArray[i-1] = i;
      int r = rand() % i;
      int temp = keyArray[r];
      keyArray[r] = keyArray[i-1];
      keyArray[i-1] = temp;
  }

  RecordId rid;
  rid.pid = 1;
  rid.sid = 2;
  btree.insert(keyArray[0],rid);
  assert(btree.getHeight()==1);
  assert(btree.getRootPid()==1);
  assert(btree.getPageFile().endPid()==2);
  BTLeafNodeTest leafnode = BTLeafNodeTest();
  leafnode.read(btree.getRootPid(),btree.getPageFile());
  assert(leafnode.getKeyCount()==1);

  char *ptr = leafnode.slotPtr(0);
  int key;
  RecordId testRid;

  memcpy(&key, ptr, sizeof(int));
  memcpy(&testRid, ptr+sizeof(int), sizeof(RecordId));
  assert(key==keyArray[0]);
  assert(testRid.pid==rid.pid);
  assert(testRid.sid==rid.sid);

  for (int i = 2; i<BTLeafNode::MAX_KEY_NUM; i++)
  {
    rid.pid = i;
    rid.sid = i+1;
    btree.insert(keyArray[i-1],rid);
    assert(btree.getHeight()==1);
    assert(btree.getRootPid()==1);
    assert(btree.getPageFile().endPid()==2);

    leafnode.read(btree.getRootPid(),btree.getPageFile());
    assert(leafnode.getKeyCount()==i);

    int eid;
    leafnode.locate(keyArray[i-1], eid);
    leafnode.readEntry(eid, key, testRid);
    assert(key==keyArray[i-1]);
    assert(testRid.pid==rid.pid);
    assert(testRid.sid==rid.sid);
  }

  assert(btree.getHeight()==1);
  assert(leafnode.getKeyCount()==BTLeafNode::MAX_KEY_NUM-1);

  //test split
  rid.pid = BTLeafNode::MAX_KEY_NUM;
  rid.sid = BTLeafNode::MAX_KEY_NUM+1;
  btree.insert(keyArray[BTLeafNode::MAX_KEY_NUM-1],rid);
  assert(btree.getHeight()==2);
  assert(btree.getRootPid()==3);

  BTNonLeafNodeTest parentnode = BTNonLeafNodeTest();
  parentnode.read(btree.getRootPid(),btree.getPageFile());
  assert(parentnode.getKeyCount()==1);
  

  ptr = parentnode.slotPtr(0);
  int leftP,rightP;
  memcpy(&leftP, ptr - sizeof(PageId), sizeof(PageId));
  memcpy(&rightP, ptr + sizeof(PageId), sizeof(PageId));
  assert(leftP==1);
  assert(rightP==2);
  leafnode.read(leftP,btree.getPageFile());
  assert(leafnode.getKeyCount()==BTLeafNode::MAX_KEY_NUM/2);
  leafnode.read(rightP,btree.getPageFile());
  assert(leafnode.getKeyCount()==BTLeafNode::MAX_KEY_NUM/2);

  for (int i = 1; i<BTLeafNode::MAX_KEY_NUM+1; i++)
  {
    rid.pid = i;
    rid.sid = i+1;

    IndexCursor ic;
    btree.locate(keyArray[i-1], ic);
    leafnode.read(ic.pid, btree.getPageFile());
    leafnode.readEntry(ic.eid, key, testRid);
    assert(key==keyArray[i-1]);
    assert(testRid.pid==rid.pid);
    assert(testRid.sid==rid.sid);
  }

  //test NonLeaf Split
  for (int i = BTLeafNode::MAX_KEY_NUM+1; i<MAX_TUPLE+1; i++)
  {
    rid.pid = i;
    rid.sid = i+1;
    btree.insert(keyArray[i-1],rid);
  }
  assert(btree.getHeight()==3);

  for (int i = 1; i<MAX_TUPLE+1; i++)
  {
    rid.pid = i;
    rid.sid = i+1;

    IndexCursor ic;
    btree.locate(keyArray[i-1], ic);
    leafnode.read(ic.pid, btree.getPageFile());
    leafnode.readEntry(ic.eid, key, testRid);
    assert(key==keyArray[i-1]);
    assert(testRid.pid==rid.pid);
    assert(testRid.sid==rid.sid);
  }

}
