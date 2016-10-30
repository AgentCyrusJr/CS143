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
 <div class="container_760">

<h3>Search a Movie or an Actor:</h3>
<hr>
<form  class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div class="form-group">
    <input type="hidden" value="on" name="searchmovie"/>
    <input type="hidden" value="on" name="searchactor"/>
</div>
<div class="form-group">
   <div class="input-append">
    <input type="text" name="search" placeholder="Search..."><input type="submit" class="btn" value="Search">
    </div>
</div>
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
            echo "<table class=\"Actortable table table-striped\">";
            echo "<tr><th>ID</th><th>title</th><th>year</th><th>rating</th><th>company</th></tr>";
            while($row = $rs->fetch_array()) {
                echo "<tr><td>{$row['id']}</td><td><a href=\"show_movie.php?mid={$row['id']}\">{$row['title']}</a></td><td>{$row['year']}</td><td>{$row['rating']}</td><td>{$row['company']}</td></tr>";
            }
            echo "</table>";
            // print 'Total results: ' . $rs->num_rows; 
        }
    }
    //if checkbox "searchactor" is on
    if($_REQUEST["searchactor"] != ""){
        //receive the input
        $searchname = $_REQUEST["search"];
        //search by name 
        //'%$searchname%' means any string which constains searchname, currently it is not case sensitive
        $query = "select * from Actor where  concat(first,' ',last) like '%$searchname%'";
        if (!($rs = $db->query($query))){ 
            $errmsg = $db->error;
            print "Query failed: $errmsg <br/>";
            exit(1);
        }else{
            echo "<h4>Actor/Actress matches \"$searchname\": <br></h4>";
            echo "<table class=\"Actortable table table-striped\">";
            echo "<tr><th>ID</th><th>name</th><th>sex</th><th>dob</th><th>dod</th></tr>";

            while($row = $rs->fetch_array()) {
                if(empty($row['dod'])){
                    $row['dod'] = "N/A";
                }
                echo "<tr><td>{$row['id']}</td><td><a href=\"show_actor.php?aid={$row['id']}\">{$row['last']} {$row['first']}</a></td><td>{$row['sex']}</td><td>{$row['dob']}</td><td>{$row['dod']}</td>";
            }
            echo "</table>";
            // print 'Total results: ' . $rs->num_rows; 
        }
    }
    
    
}
?>
</div>
</body>
</html>