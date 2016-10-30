 <div class="container_760">

 	<?php
 	require_once("directorService.class.php");
 	require_once("movieService.class.php");
 	$directorService = new directorService();
 	$movieservice = new movieService();
 	$directors = $directorService->getAllDirectors();
 	$movies = $movieservice->getAllMovies();
 	?>
	 <form action="insert_director_movie.php" method="post">
		<div class="form-group">
	    <label for="MAMovie">Movie Title:</label>
	    <select class="form-control" id="MAMovie" name="MDMovie">
	    	<?php
		    	for($i=0;$i<count($movies);$i++)
				{
					$row = $movies[$i];
					echo "<option value='{$row['id']}'>{$row['title']}</option>";
				}
	    	?>
	    	
	    </select>
	  </div>
	  <div class="form-group">
	    <label for="MAMovie">Director:</label>
	    <select class="form-control" id="MAActor" name="MDDirector">
	    	<?php
		    	for($i=0;$i<count($directors);$i++)
				{
					$row = $directors[$i];
					echo "<option value='{$row['id']}'>{$row['last']} {$row['first']}</option>";
				}
	    	?>
	    </select>
	  </div>
	  <button type="submit" class="btn btn-default">Submit</button>
	</form>
 </div>