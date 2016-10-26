<!DOCTYPE html>
<html>
<body>

<head><title>  
Search Query
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />



<?php
$servername = "localhost";
$username = "cs143";
$password = "";
$dbname = "CS143"

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <h3>Search a Movie or an Actor:</h3>
  <INPUT TYPE="checkbox" NAME="searchmovie" VALUE="on" > I want to search for a Movie
  <INPUT TYPE="checkbox" NAME="searchactor" VALUE="on" > I want to search for an actor
  <br><br>
  <input type="text" name="search" placeholder="Search..." size=25 maxlength=30>
  <br><br>
  <input type="submit" value= "Done">
</form>


<?php
//connect to the database
$db = new mysqli($servername, $username, $password, $dbname);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //if checkbox "searchmovie is on"
    if($_REQUEST["searchmovie"] != ""){
        //receive the input
        $searchname = $_REQUEST["search"];
        //search by name 
        //'%$searchname%' means any string which constains searchname, currently it is not case sensitive
        $query = "select * from Movie where title like '%$searchname%'";
        if (!($rs = $db->query($query))){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br />";
            exit(1);
        }else{
            echo "<h4>Movie matches \"$searchname\":<br></h4>";
            while($row = $rs->fetch_array()) {
                for($x = 0; $x < count($row); $x ++){
                    print "$row[$x] ";
                }
                echo "<br>";
            }
            // print 'Total results: ' . $rs->num_rows; 
        }
    }
    //if checkbox "searchactor" is on
    if($_REQUEST["searchactor"] != ""){
        //receive the input
        $searchname = $_REQUEST["search"];
        //search by name 
        //'%$searchname%' means any string which constains searchname, currently it is not case sensitive
        $query = "select * from Actor where first like '%$searchname%'  or last like '%$searchname%'";
        if (!($rs = $db->query($query))){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Actor/Actress matches \"$searchname\": <br></h4>";
            while($row = $rs->fetch_array()) {
                for($x = 0; $x < count($row); $x ++){
                    print "$row[$x] ";
                }
                echo "<br>";
            }
            // print 'Total results: ' . $rs->num_rows; 
        }
    }
    
    
}
?>

</body>
</html>