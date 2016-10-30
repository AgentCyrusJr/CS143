<!DOCTYPE html>
<html>

<head><title>  
Show a Movie
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
</head>
<link rel="stylesheet" href="style/reset.css" type="text/css"/>
<link rel="stylesheet" href="style/text.css" type="text/css"/>
<link rel="stylesheet" href="style/bootstrap.css" type="text/css"/>
<link rel="stylesheet" href="style/page.css" type="text/css"/>
<link rel="stylesheet" href="style/960_16_col.css" type="text/css"/>
<body>
<?php
require_once("SqlTool.class.php");
    require("Header.php");?>
<div class="container">
<div class="container">
    <?php 
    require "index_carousel.php";
    ?>
    </div>
<?php

	$servername = "localhost";
	$username = "cs143";
	$password = "";
	$dbname = "CS143";
    

	$db = new mysqli($servername, $username, $password, $dbname);

	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}

	if(!empty($_GET['mid']))
	{
		$MovieId = $_GET['mid'];
    }else{
        if(!empty($_POST['mid'])){
            $MovieId = $_POST['mid'];
        }else{
        $MovieId = 1;

        }
    }
    //print_r($_POST);
	$sql_show_movie = "Select * from Movie where id = $MovieId"; 
    //echo $sql_show_movie;
	
    //echo "123";
	$sqltool = new SqlTool();
    //echo "123";
	$res = $sqltool->execute_dql2($sql_show_movie);

	// $sqltool->finish();
	// $res = $db->query($sql_show_actor);
    //echo "test";
 	if (!$res){ 
            $errmsg = $db->error;
            print "No Result <br/>";
        }else{
            echo "<h4>Result:</h4>";
            echo "<table class=\"table table-striped\">";
            echo "<tr><th>id</th><th>title</th><th>year</th><th>rating</th><th>company</th></tr>";
            for($i=0;$i<count($res);$i++)
			{
                //print_r($res);
				$row = $res[$i];
				echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['year']}</td><td>{$row['rating']}</td><td>{$row['company']}</td></br>";
			}
            echo "</table>";

            // print 'Total results: ' . $rs->num_rows; 
        }

 	echo"<br>";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //receive the input
        $commenter_name = $_REQUEST["name"];
        $rate     = $_REQUEST["rating"];
        $context    = $_REQUEST["comments"];
        
        $create_comment = "insert into Review values('$commenter_name', date(\"Y/m/d\"), $MovieId, $rate, '$context')";
        if ($db->query($create_comment)) {
            //echo "New comment has been made";
        } else {
            echo "Error: " . $create_comment . "<br>" . mysqli_error($conn);
        }
    }
    
        $show_role_title = "Select Actor.id, CONCAT(Actor.first, ' ', Actor.last) name, role from Actor left join MovieActor on MovieActor.aid = Actor.id where MovieActor.mid = $MovieId";
    $res1 = $sqltool->execute_dql2($show_role_title);
    if (!$res1){ 
            $errmsg = $db->error;
            print "No Result <br/>";
        }else{
            echo "<h4>Role list: <br></h4>"; 
            echo "<table class=\"table table-striped\">";
            echo "<tr><th>Name</th><th>Role</th></tr><br>";
            for($i=0;$i<count($res1);$i++)
            {
                $row = $res1[$i];
                //print_r($row);
                echo "<tr><td><a href= \"show_actor.php?aid={$row['id']}\">{$row['name']}</a></td><td>{$row['role']}</td>";
            }
            // print 'Total results: ' . $rs->num_rows; 
            echo "</table>";
        }
$show_averge = "Select AVG(rating) avg from Review where mid = $MovieId";
    $res2 = $sqltool->execute_dql2($show_averge);
    if (!$res2){ 
            $errmsg = $db->error;
            print "No Result <br/>";
        }else{
            for($i=0;$i<count($res2);$i++)
            {
                $row = $res2[$i];
                if(empty($row['avg'])){
                    $row['avg'] = 0;
                }
                echo "<b>Rating: </b>";
                echo "{$row['avg']}";
            }
            // print 'Total results: ' . $rs->num_rows; 
        }

    echo "<hr/><h2>Make a Review:</h2>";
?>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <div class="form-group">
        
        Name:<input type="textarea" class="form-control" placeholder="Text input"  name="name"/>
    </div>

 Rate the Movie:
        <input type="radio" checked="checked" name="rating" value="1"/>1


        <input type="radio" checked="checked" name="rating" value="2"/>2

        <input type="radio" checked="checked" name="rating" value="3"/>3

        <input type="radio" checked="checked" name="rating" value="4"/>4

        <input type="radio" checked="checked" name="rating" value="5"/>5


  	<div class="form-group">
 	 	<label for="Comments">Comments:<br></label>
 	 	<textarea name="comments" rows="10" cols="600" placeholder="no more than 500 characters..."></textarea>
        <?php echo "<input type=\"hidden\" value=\"$MovieId\" name=\"mid\"/>"; 
        ?>
 	 	<input type="submit" value= "Done">
 	 </div>
	</form>
    <hr/>


<?php

    echo "<h4><b>Comment details shown below :</b></h4><br>";
    $show_comment = "Select * from Review where mid = $MovieId";
    $res3 = $sqltool->execute_dql2($show_comment);
    //print_r($res3);
    if (!$res3){ 
            $errmsg = $db->error;
            print "No Result <br/>";
        }else{
        	//print_r($res3);
            echo "<table class=\"table table-striped\">";
            echo "<tr><th>Comment</th><th>name</th><th>time</th></tr>";
        	for($i=0;$i<count($res3);$i++)
			{
				$row = $res3[$i];
                //print_r($row);
				echo "<tr><td>{$row['comment']}</td><td>{$row['name']}</td><td>{$row['time']}</td></tr>";
			}
            // print 'Total results: ' . $rs->num_rows; 
            echo "</table>";
        }

require("footer.php");
?>

</body>
</html>