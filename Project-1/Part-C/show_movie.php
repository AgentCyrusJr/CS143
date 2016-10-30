<!DOCTYPE html>
<html>
<body>

<head><title>  
Show a Movie
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />

<?php
	require_once("actorService.class.php");
	require_once("config.php");
	require_once("DivideTool.class.php");
	require_once("SqlTool.class.php");

	$servername = "localhost";
	$username = "cs143";
	$password = "";
	$dbname = "CS143";
	$db = new mysqli($servername, $username, $password, $dbname);

	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}

	if(!empty($_GET['Mid']))
		{
			$MovieId = $_GET['Mid'];
		}

	$MovieId = 100;
	$sql_show_movie = "Select * from Movie where id = $MovieId"; 
	
	$sqltool = new SqlTool();
	$res = $sqltool->execute_dql2($sql_show_movie);

	// $sqltool->finish();
	// $res = $db->query($sql_show_actor);

 	if (!$res){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Movie matches \"$MovieId\": <br></h4>";
            echo "<tr><th>id</th><th>title</th><th>year</th><th>rating</th><th>company</th></tr><br>";
            for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['year']}</td><td>{$row['rating']}</td><td>{$row['company']}</td></br>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }

 	echo"<br>";
    $show_averge = "Select AVG(rating) avg from Review where mid = $MovieId";
    $res2 = $sqltool->execute_dql2($show_averge);
    if (!$res2){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            for($i=0;$i<count($res2);$i++)
			{
				$row = $res2[$i];
				echo "<b>Rating: </b>";
				echo "<tr><td>{$row['avg']}</td></tr>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }

    echo "<h5>Make a Review:</h5><br>";
?>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <div class="form-group">
        <label for="Name">Name<br></label>
        <input type="text" class="form-control" placeholder="Text input"  name="name"/>
    </div>

    <h2>Rate the Movie:</h2>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="rating" value="1"/>1
    </label>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="rating" value="2"/>2
    </label>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="rating" value="3"/>3
    </label>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="rating" value="4"/>4
    </label>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="rating" value="5"/>5
    </label>

  	<div class="form-group">
 	 	<label for="Comments">Comments:<br></label>
 	 	<textarea name="comments" rows="10" cols="60" placeholder="no more than 500 characters..."></textarea>
   		<br><br>
 	 	<input type="submit" value= "Done">
 	 </div>
	</form>


<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    //receive the input
	    $commenter_name = $_REQUEST["name"];
	    $rate     = $_REQUEST["rating"];
	    $context    = $_REQUEST["comments"];
	    
	    $create_comment = "insert into Review values('$commenter_name', date(\"Y/m/d\"), $MovieId, $rate, '$context')";
	    if ($db->query($create_comment)) {
	        echo "New comment has been made";
	    } else {
	        echo "Error: " . $create_comment . "<br>" . mysqli_error($conn);
	    }
	}


    $show_role_title = "Select Actor.id, CONCAT(Actor.first, ' ', Actor.last) name, role from Actor left join MovieActor on MovieActor.aid = Actor.id where MovieActor.mid = $MovieId";
    $res1 = $sqltool->execute_dql2($show_role_title);
	if (!$res1){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Role list: <br></h4>"; 
    		echo "<tr><th>Name</th><th>Role</th></tr><br>";
            for($i=0;$i<count($res1);$i++)
			{
				$row = $res1[$i];
				print_r($row);
				echo "<tr><a href= \"show_actor.php?aid={$row['id']}\">{$row['name']}</a><td>{$row['name']}</td><td>{$row['role']}</td></br>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }

    echo "<h4><b>Comment details shown below :</b></h4><br>";
    $show_comment = "Select * from Review where mid = $MovieId";
    $res3 = $sqltool->execute_dql2($show_comment);
    if (!$res3){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
        	print_r($res3);
        	for($i=0;$i<count($res3);$i++)
			{
				$row = $res3[$i];
				echo "<tr><td>{$row['comment']}</td>{$row['name']}</td>{$row['time']}</td></tr><br>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }


?>

</body>
</html>