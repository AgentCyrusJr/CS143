<!DOCTYPE html>
<html>
<body>

<head><title>  
Add a Person
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />

<?php
$servername = "localhost";
$username = "cs143";
$password = "";
$dbname = "CS143"
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <h2>Add a Person:</h2>
    <label class="radio-inline">
        <input type="radio" checked="checked" name="identity" value="Actor"/>Actor
    </label>
    <label class="radio-inline">
        <input type="radio" name="identity" value="Director"/>Director
    </label>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" placeholder="Text input"  name="fname"/>
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" placeholder="Text input" name="lname"/>
    </div>
        <label class="radio-inline">
        <input type="radio" name="sex" checked="checked" value="male">Male
        </label>
        <label class="radio-inline">
        <input type="radio" name="sex" value="female">Female
        </label>
        <div class="form-group">
        <label for="DOB">Date of Birth</label>
        <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="dateb">ie: 1970-01-01<br>
    </div>
    <div class="form-group">
        <label for="DOD">Date of Death</label>
        <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="dated">(if alive, just leave blank)<br>
    </div>
    <button type="submit" class="btn btn-default">Add!</button>
</form>


<?php
//connect to the database
$db = new mysqli($servername, $username, $password, $dbname);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //receive the input
    $person_identity = $_REQUEST["identity"];
    $first_name      = $_REQUEST["fname"];
    $last_name       = $_REQUEST["lname"];
    $person_sex      = $_REQUEST["sex"];
    $person_birth    = $_REQUEST["dateb"];
    //if there is no input for the dated, then it will store 0000-00-00 in the table
    $person_death    = $_REQUEST["dated"];
    
    if($person_identity == 'Actor'){
        $sql = "select max(id) from Actor";
        $row = $db->query($sql)->fetch_array(); 
        $newactorid = $row[0]+1;
        $addactor = "insert into Actor values($newactorid,'$first_name','$last_name','$person_sex','$person_birth', '$person_death')";
        if ($db->query($addactor)) {
           echo "New record in Actor created successfully";
        } else {
            echo "Error: " . $addactor . "<br>" . mysqli_error($conn);
        }
    }else{
        $sql = "select max(id) from Director";
        $row = $db->query($sql)->fetch_array(); 
        $newdirectorid = $row[0]+1;
        $adddirector = "insert into Director values($newdirectorid,'$first_name','$last_name','$person_birth', '$person_death')";
        if ($db->query($adddirector)) {
           echo "New record in Director created successfully";
        } else {
            echo "Error: " . $adddirector . "<br>" . mysqli_error($conn);
        }
    }
    
    
}
?>

</body>
</html>