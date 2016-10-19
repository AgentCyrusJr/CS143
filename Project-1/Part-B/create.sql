# Create all the tables related

CREATE TABLE Movie(
						id  	INT,
						title 	VARCHAR(100) Not NULL,
						year	INT,
						rating	VARCHAR(10),
						company	VARCHAR(50),
						PRIMARY KEY(id),
						CHECK(year>0)) ENGINE=INNODB;
# we set id as primary key and set a constraint that the movie year should larger than 0

CREATE TABLE Actor(
						id  	INT,
						last 	VARCHAR(20),
						first	VARCHAR(20),
						sex		VARCHAR(6),
						dob		DATE,
						dod		DATE,
						PRIMARY KEY(id),
						constraint check_dates check (dob < dod)) ENGINE=INNODB;

# we set id as primary key and set a constraint that the dead date should be later than birth date
						
CREATE TABLE Director(
						id  	INT,
						last 	VARCHAR(20),
						first	VARCHAR(20),
						dob		DATE,
						dod		DATE,
						PRIMARY KEY(id),
						constraint check_dates check (dob < dod)) ENGINE=INNODB;
# the same as above table, we set id as primary key and set a constraint that the dead date should be later than birth date

CREATE TABLE MovieGenre(
						mid  	INT,
						genre	VARCHAR(20),
						PRIMARY KEY(mid),
						FOREIGN KEY (mid) references Movie(id)) ENGINE=INNODB;
# we set mid as primary key and set a foreign key refer to primary key in Movie

CREATE TABLE MovieDirector(
						mid  	INT,
						did		INT,
						PRIMARY KEY(mid,did),
						FOREIGN KEY (mid) references Movie(id),
						FOREIGN KEY (did) references Director(id)) ENGINE=INNODB;
# we set mid, did as primary key and set two foreign key, mid refer to primary key in Movie and did refer to primary key in Director table

CREATE TABLE MovieActor(
						mid  	INT,
						aid		INT,
						role	VARCHAR(50) NOT NULL,
						PRIMARY KEY(mid,aid),
						FOREIGN KEY (mid) references Movie(id),
						FOREIGN KEY (aid) references Actor(id)) ENGINE=INNODB;
# the same as above table, we set mid, did as primary key and set two foreign key, mid refer to primary key in Movie and did refer to primary key in Director table. we also set that role can not be null

CREATE TABLE Review(
						name	VARCHAR(20) NOT NULL,
						time	TIMESTAMP,
						mid  	INT,
						rating	INT,
						comment	VARCHAR(500),
						PRIMARY KEY(name,time,mid),
						FOREIGN KEY (mid) references Movie(id),
						CHECK(time < CURRENT_TIMESTAMP AND rating >= 0)) ENGINE=INNODB;
# we set mid, did and name as primary key and set two foreign key, mid refer to primary key in Movie. we also set a constraint that time should be prior to current time and rating should lager than 0

CREATE TABLE MaxPersonID(id  	INT);

CREATE TABLE MaxMovieID(id  	INT);