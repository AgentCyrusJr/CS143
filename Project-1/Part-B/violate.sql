Insert into Movie (id, title, year, rating,company) values(1,'test',-1,'rating','company');
# it will violate the rule that year should not less than 0
Insert into Movie (id, title, year, rating,company) values(1,'test',1,'rating','company');
Insert into Movie (id, title, year, rating,company) values(1,'test',1,'rating','company');
# it will violate the unique of primary key

Insert into Actor (id, last, first, sex,dob,dod) values(1,'last','first','female','2012-01-01', '2012-01-01');
# it will violate the rule that dead date should prior to birthday

Insert into MovieGenre (mid, genre) values(111111111,'test');
# it will violate the foreign key rule, 111111111 may not exist at Movie table.

Insert into MovieDirector (mid,did) values(111111111,00000000);
# it will violate the foreign key rule, 111111111 and 00000000 may not exist at Movie table and Director table.

Insert into MovieActor (mid,aid,role) values(111111111,00000000);
# it will violate the foreign key rule, 111111111 and 00000000 may not exist at Movie table and Actor table. And role should not be Null too.

Insert into Review (name, time, mid, rating, comment) values('', '2112-01-01', 00000000, -1,'comment');
# it will violate the foreign key rule, 00000000 may not exist at Movie tabl. And name should not be Null. Time should be prior to current date. Rating should larger than 0.
