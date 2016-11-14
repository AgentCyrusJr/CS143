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

#define UNIT_TEST_RANDOM_MAX 200
#define UNIT_TEST_RANDOM_MIN 0
#define MAX_TUPLE 12000

class BTIndexTest : public BTreeIndex
{
public:    
        void print() 
        {
        }
};

class BTLeafNodeTest : public BTLeafNode
{
public:    
        void print() 
        {
        }
};

class BTNonLeafNodeTest : public BTNonLeafNode
{
        void print( ) 
        {
        }
};


#endif /* UNIT_TEST_H */
