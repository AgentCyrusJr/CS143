#1. Give me the names of all the actors in the movie 'Die Another Day'. Please also make sure actor names are in this format:  
# <firstname> <lastname>  (seperated by single space, **very important**).

SELECT 	CONCAT(first, ' ', last)
FROM 	Actor
WHERE	Actor.id IN (SELECT MovieActor.aid
					 FROM 	Movie, MovieActor	
					 WHERE	Movie.title = 'Die Another Day'
					 			AND Movie.id = MovieActor.mid);



#2. Give me the count of all the actors who acted in multiple movies.

SELECT 	COUNT(S.aid)
FROM 	(SELECT 	aid
		 FROM 		MovieActor
		 GROUP BY 	MovieActor.aid
		 HAVING 	COUNT(*)>1) N;

# There is another feasible solution of query 2

-- SELECT 	COUNT(id)
-- FROM 	Actor
-- WHERE	Actor.id IN (SELECT 	MovieActor.aid
-- 					 FROM 		MovieActor
-- 					 GROUP BY 	MovieActor.aid
-- 					 HAVING 	COUNT(*)>1);

#3. Find the number of people is both an actor and a director.

SELECT 	COUNT(*)
FROM 	Director, Actor
WHERE 	Director.id = Actor.id; 
