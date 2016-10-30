 <div class="container_760">

 	<?php
 	require_once("actorService.class.php");
 	require_once("movieService.class.php");
 	$actorservice = new actorService();
 	$movieservice = new movieService();
 	$actors = $actorservice->getAllActors();
 	$movies = $movieservice->getAllMovies();
 	?>
	 <form action="insert_actor_movie.php" method="post">
		<div class="form-group">
	    <label for="MAMovie">Movie Title:</label>
	    <select class="form-control" id="MAMovie" name="MAMovie">
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
	    <label for="MAMovie">Actor:</label>
	    <select class="form-control" id="MAActor" name="MAActor">
	    	<?php
		    	for($i=0;$i<count($actors);$i++)
				{
					$row = $actors[$i];
					echo "<option value='{$row['id']}'>{$row['last']} {$row['first']}</option>";
				}
	    	?>
	    </select>
	  </div>
	  <div class="form-group">
	    <label for="MARole">Role:</label>
	    <input id = "MARole" name="MARole" class="form-control" type="text" placeholder="role">
	  </div>
	  <button type="submit" class="btn btn-default">Submit</button>
	</form>
 </div>