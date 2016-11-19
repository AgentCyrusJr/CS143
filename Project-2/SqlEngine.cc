/**
 * Copyright (C) 2008 by The Regents of the University of California
 * Redistribution of this file is permitted under the terms of the GNU
 * Public License (GPL).
 *
 * @author Junghoo "John" Cho <cho AT cs.ucla.edu>
 * @date 3/24/2008
 */

#include <cstdio>
#include <cstring>
#include <cstdlib>
#include <iostream>
#include <fstream>
#include "Bruinbase.h"
#include "SqlEngine.h"
#include "BTreeIndex.h"
#include "BTreeNode.h"

using namespace std;

// external functions and variables for load file and sql command parsing 
extern FILE* sqlin;
int sqlparse(void);


RC SqlEngine::run(FILE* commandline)
{
  fprintf(stdout, "Bruinbase> ");

  // set the command line input and start parsing user input
  sqlin = commandline;
  sqlparse();  // sqlparse() is defined in SqlParser.tab.c generated from
               // SqlParser.y by bison (bison is GNU equivalent of yacc)

  return 0;
}

RC SqlEngine::select(int attr, const string& table, const vector<SelCond>& cond)
{
	
  RecordFile rf;   // RecordFile containing the table
  RecordId   rid;  // record cursor for table scanning

  RC     rc;
  int    key;     
  string value;
  int    count;
  int    diff;

  BTreeIndex btreeindex;
  IndexCursor cursor;


  // open the table file
  if ((rc = rf.open(table + ".tbl", 'r')) < 0) {
    fprintf(stderr, "Error: table %s does not exist\n", table.c_str());
    return rc;
  }

  // scan the table file from the beginning
  rid.pid = rid.sid = 0;
  count = 0;
  int search_key = 0;
  if (btreeindex.open(table + ".idx", 'r') == 0) { //index : true 
	  int eq = SelCond::EQ;
	  int ne = SelCond::NE;
	  int lt = SelCond::LT;
	  int gt = SelCond::GT;
	  int le = SelCond::LE;
	  int ge = SelCond::GE;

	  //set a loop to scan for the conditions in the array
	  for (int i = 0; i < cond.size(); i++) { 
		  if (cond[i].attr != 1)   // attr == 2 means value
			  continue;
		  else if (cond[i].comp == eq) {
			  search_key = atoi(cond[i].value);
			  break;
		  }
		  else if (cond[i].comp == ge) {
			  if (search_key == 0) {
				  search_key = atoi(cond[i].value);
				  continue;
			  }
			  int compareval = atoi(cond[i].value);
			  if (compareval > search_key)
				  search_key = compareval;
		  }
		  else if (cond[i].comp == gt) {
			  if (search_key == 0) {
				  search_key = atoi(cond[i].value);
				  continue;
			  }
			  int compareval = atoi(cond[i].value) + 1;
			  if (compareval > search_key)
				  search_key = compareval;
		  }
		  //we don't need to compare when case is lt or le, since we will pick 
		  //always the front element until the search key
	  }
	  btreeindex.locate(search_key, cursor);

	  while (btreeindex.readForward(cursor, key, rid) == 0) { 
		  if (attr != 4) { //we don't want values when doing count(*)
			  if ((rc = rf.read(rid, key, value)) < 0) {
				  fprintf(stderr, "Error: while reading a tuple from table %s\n", table.c_str());
				  goto exit_select;
			  }
		  }
		  // check the conditions on the tuple
		  for (unsigned i = 0; i < cond.size(); i++) {
			  // difference between the tuple value and the condition value
			  switch (cond[i].attr) {
			  case 1:
				  diff = key - atoi(cond[i].value);
				  break;
			  case 2:
				  diff = strcmp(value.c_str(), cond[i].value);
				  break;
			  }

			  // skip the tuple if any condition is not met
			  switch (cond[i].comp) {
			  case SelCond::EQ:
				  if (diff != 0)
					  if (cond[i].attr == 1) goto exit_select; 
					  else continue; 
				  break;
			  case SelCond::NE:
				  if (diff == 0) continue;
				  break;
			  case SelCond::GT: // no duplicate tolerance
				  if (diff <= 0) continue;
				  break;
			  case SelCond::LT:
				  if (diff >= 0) // no duplicate tolerance
					  if (cond[i].attr == 1) goto exit_select; 
					  else continue; 
				  break;
			  case SelCond::GE:
				  if (diff < 0) continue;
				  break;
			  case SelCond::LE:
				  if (diff > 0) 
					  if (cond[i].attr == 1) goto exit_select; 
					  else continue;
				  break;
			  }
		  }

		  // the condition is met for the tuple. 
		  // increase matching tuple counter
		  count++;

		  // print the tuple 
		  switch (attr) {
		  case 1:  // SELECT key
			  fprintf(stdout, "%d\n", key);
			  break;
		  case 2:  // SELECT value
			  fprintf(stdout, "%s\n", value.c_str());
			  break;
		  case 3:  // SELECT *
			  fprintf(stdout, "%d '%s'\n", key, value.c_str());
			  break;
		  }
	  }
  }
  else
	  while (rid < rf.endRid()) {
		  // read the tuple
		  if ((rc = rf.read(rid, key, value)) < 0) {
			  fprintf(stderr, "Error: while reading a tuple from table %s\n", table.c_str());
			  goto exit_select;
		  }

		  // check the conditions on the tuple
		  for (unsigned i = 0; i < cond.size(); i++) {
			  // compute the difference between the tuple value and the condition value
			  switch (cond[i].attr) {
			  case 1:
				  diff = key - atoi(cond[i].value);
				  break;
			  case 2:
				  diff = strcmp(value.c_str(), cond[i].value);
				  break;
			  }

			  // skip the tuple if any condition is not met
			  switch (cond[i].comp) {
			  case SelCond::EQ:
				  if (diff != 0) goto next_tuple;
				  break;
			  case SelCond::NE:
				  if (diff == 0) goto next_tuple;
				  break;
			  case SelCond::GT:
				  if (diff <= 0) goto next_tuple;
				  break;
			  case SelCond::LT:
				  if (diff >= 0) goto next_tuple;
				  break;
			  case SelCond::GE:
				  if (diff < 0) goto next_tuple;
				  break;
			  case SelCond::LE:
				  if (diff > 0) goto next_tuple;
				  break;
			  }
		  }

		  // the condition is met for the tuple. 
		  // increase matching tuple counter
		  count++;

		  // print the tuple 
		  switch (attr) {
		  case 1:  // SELECT key
			  fprintf(stdout, "%d\n", key);
			  break;
		  case 2:  // SELECT value
			  fprintf(stdout, "%s\n", value.c_str());
			  break;
		  case 3:  // SELECT *
			  fprintf(stdout, "%d '%s'\n", key, value.c_str());
			  break;
		  }

		  // move to the next tuple
	  next_tuple:
		  ++rid;
	  }

  // print matching tuple count if "select count(*)"
  if (attr == 4) {
	  fprintf(stdout, "%d\n", count);
  }
  rc = 0;

  // close the table file and return
exit_select:
  rf.close();
  return rc;
}

RC SqlEngine::load(const string& table, const string& loadfile, bool index)
{
  //by ACJ 2016/11/05
	//create a RecordFile class, and name it as <tablename> + ".tbl"
	RecordFile new_table;
	new_table.open(table + ".tbl", 'w');
	RecordId rid;
	rid.pid = rid.sid = 0;
	BTreeIndex btreeindex; // B+ tree for index
	RC rc = 0;
	// Open the file required to be loaded
	ifstream current_file(loadfile.c_str());
	if (index) {
		
		rc = btreeindex.open((table + ".idx").c_str(), 'w');
		if (rc != 0) {
			fprintf(stderr, "Error: index %s file could not be opened for writing\n", loadfile.c_str());
			new_table.close();
			current_file.close();
			return rc; //RC_FILE_OPEN_FAILED;
		}
	}


	// Read file 
	if (current_file.is_open()) {

		string current_line;
		int key;
		string value;
		RecordId rid = new_table.endRid();
		
		while (!current_file.eof()) {
			getline(current_file, current_line);
			//SqlEngine::parseLoadLine
			parseLoadLine(current_line, key, value);
			new_table.append(key, value, rid);
			rid++;

			if (index) { 
				rc = btreeindex.insert(key, rid);
				if (rc != 0) {
					fprintf(stderr, "Error: cannot insert key into index %s file\n", loadfile.c_str());
					new_table.close();
					current_file.close();
					return rc;
				}
			}

		}
	}

	// Close file and table
	current_file.close();
	new_table.close();
	if (index) {
		btreeindex.close();
	}
	return 0;
}

RC SqlEngine::parseLoadLine(const string& line, int& key, string& value)
{
    const char *s;
    char        c;
    string::size_type loc;
    
    // ignore beginning white spaces
    c = *(s = line.c_str());
    while (c == ' ' || c == '\t') { c = *++s; }

    // get the integer key value
    key = atoi(s);

    // look for comma
    s = strchr(s, ',');
    if (s == NULL) { return RC_INVALID_FILE_FORMAT; }

    // ignore white spaces
    do { c = *++s; } while (c == ' ' || c == '\t');
    
    // if there is nothing left, set the value to empty string
    if (c == 0) { 
        value.erase();
        return 0;
    }

    // is the value field delimited by ' or "?
    if (c == '\'' || c == '"') {
        s++;
    } else {
        c = '\n';
    }

    // get the value string
    value.assign(s);
    loc = value.find(c, 0);
    if (loc != string::npos) { value.erase(loc); }

    return 0;
}
