<!DOCTYPE html>
<html>
<body>

<head><title>  
My Database 
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Input the query in the box:<br><br> 
  <textarea name="expression" rows="10" cols="60"></textarea>
  <br><br>
  <input type="submit" value= "Done">
</form>

<?php
$db = new mysqli('localhost', 'cs143', '', 'CS143');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "select * from Movie";
    //Three feasible query expressions:
    //1.
    //$query = "SELECT * FROM Movie";
    //2.
    //$query = "SELECT * FROM Actor WHERE id < 20";
    //3. this one might take a little time around several minutes
    //$query = "select * 
    //          from Actor 
    //          where Actor.id in (select MovieActor.aid 
    //                             from Movie, MovieActor
    //                             where Movie.title = 'Die Another Day' 
    //                                        and Movie.id = MovieActor.mid)";
    // 
    $query = $_REQUEST['expression'];
    if (!($rs = $db->query($query))){ 
        $errmsg = $db->error;
        print "Query failed: $errmsg <br />";
        exit(1);
    }else{
	
        print "<h3>Result from MySQL:</h3>";
		print "<table border=1 cellspacing=1 cellpadding=2>";
		$head = false;
        while($row = $rs->fetch_assoc()) {
            //iterate the rows and print each attributes out
			if(!$head){
				print "<tr align=center>";
				$keys = array_keys($row);
				foreach ($keys as $k){
					print "<td><b>$k</b></td>";
				}
				print "</tr>";
				$head = true;
			}
			print "<tr align=center>";
			
			
            foreach ($row as $value){
				if(empty($value)){
					print "<td>N/A</td>";
				}
				else {
					print "<td>$value</td>";
				}
            }
            print "</tr>";
			
        }
		echo "</table>";
        print 'Total results: ' . $rs->num_rows; 
    }
    
}
?>

</body>
</html>