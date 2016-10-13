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
$db = new mysqli('localhost', 'cs143', '', 'TEST');

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
        echo "Result:<br>";
        while($row = $rs->fetch_array()) {
            //iterate the rows and print each attributes out
            for($x = 0; $x < count($row); $x ++){
                print "$row[$x] ";
            }
            echo "<br>";
        }
        print 'Total results: ' . $rs->num_rows; 
    }
    
}
?>

</body>
</html>
