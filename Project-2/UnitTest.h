/*
 * Copyright (C) 2008 by The Regents of the University of California
 * Redistribution of this file is permitted under the terms of the GNU
 * Public License (GPL).
 *
 * @author Junghoo "John" Cho <cho AT cs.ucla.edu>
 * @date 3/24/2008
 */

#ifndef UNIT_TEST_H
#define UNIT_TEST_H

#include <iostream>
#include "BTreeIndex.h"
#include "BTreeNode.h"

#define MAX_TUPLE 12000

class BTLeafNodeTest : public BTLeafNode
{
public:    
  void printNode();
};

class BTNonLeafNodeTest : public BTNonLeafNode
{
public:
  void printNode();
};


#endif /* UNIT_TEST_H */
