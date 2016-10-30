<!DOCTYPE html>
<html>
<body>

<head><title>  
Show an Actor
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

	if(!empty($_GET['aid']))
		{
			$ActorId = $_GET['aid'];
		}

	// $ActorId = 597;
	$sql_show_actor = "Select * from Actor where id = $ActorId"; 
	
	$sqltool = new SqlTool();
	$res = $sqltool->execute_dql2($sql_show_actor);

	// $sqltool->finish();
	// $res = $db->query($sql_show_actor);

 	if (!$res){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Actor/Actress matches \"$ActorId\": <br></h4>";
            echo "<tr><th>ID</th><th>last</th><th>first</th><th>sex</th><th>dob</th><th>dod</th></tr><br>";
            for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				echo "<tr><td>{$row['id']}</td><td>{$row['last']}</td><td>{$row['first']}</td><td>{$row['sex']}</td><td>{$row['dob']}</td><td>{$row['dod']}</td></br>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }

 
    $show_role_title = "Select MovieActor.role, Movie.title from MovieActor left join Movie on MovieActor.mid = Movie.id where MovieActor.aid = $ActorId";
    $res1 = $sqltool->execute_dql2($show_role_title);
	if (!$res1){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Role and Movies: <br></h4>"; 
    		echo "<tr><th>Role</th><th>Movie Title</th></tr><br>";
            for($i=0;$i<count($res1);$i++)
			{
				$row = $res1[$i];
				echo "<tr><td>{$row['role']}</td><td>{$row['title']}</td></br>";
			}
            // print 'Total results: ' . $rs->num_rows; 
        }

?>

</body>
</html>