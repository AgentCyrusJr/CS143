<!DOCTYPE html>
<html>
<body>

<head><title>  
Add a Movie
</title>  
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />

<?php
$servername = "localhost";
$username = "cs143";
$password = "";
$dbname = "CS143"
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <h2>Add a Movie:</h2>
    <b>Movie title:</b>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Movie title" name="title">
    </div>
    <b>Movie Company:</b>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Movie Company" name="company">
    </div>
    <b>Year released:</b>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Year released" name="year">
    </div>
    <b>MPAA Rating:</b>
    <div class="form-group">
        <select   class="form-control" name="rate">
            <option value="G">G</option>
            <option value="NC-17">NC-17</option>
            <option value="PG">PG</option>
            <option value="PG-13">PG-13</option>
            <option value="R">R</option>
            <option value="surrendere">surrendere</option>
        </select>
    </div>
    <b>Movie Genre:</b>
    <div class="form-group">
        <select  class="form-control" name="genre">
            <option value="Action">Action</option>
            <option value="Adult">Adult</option>
            <option value="Adventure">Adventure</option>
            <option value="Animation">Animation</option>
            <option value="Comedy">Comedy</option>
            <option value="Crime">Crime</option>
            <option value="Documentary">Documentary</option>
            <option value="Drama">Drama</option>
            <option value="Family">Family</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Horror">Horror</option>
            <option value="Musical">Musical</option>
            <option value="Mystery">Mystery</option>
            <option value="Romance">Romance</option>
            <option value="Sci-Fi">Sci-Fi</option>
            <option value="Short">Short</option>
            <option value="Thriller">Thriller</option>
            <option value="War">War</option>
            <option value="Western">Western</option>
        </select>   
    </div>
    <br><br>
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
    $titlename  = $_REQUEST["title"];
    $companyname= $_REQUEST["company"];
    $movieyear  = $_REQUEST["year"];
    $movierate  = $_REQUEST["rate"];
    $moviegenre = $_REQUEST["genre"];
    
    $sql = "select max(id) from Movie";
    $row = $db->query($sql)->fetch_array(); 
    $newmovieid = $row[0]+1;

    $query1 = "insert into Movie values($newmovieid,'$titlename',$movieyear,'$movierate','$companyname')";
    $query2 = "insert into MovieGenre values($newmovieid,'$moviegenre')";
    if ($db->query($query1)) {
        echo "New record in Movie created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    if ($db->query($query2)) {
        echo "New record in MovieGenre created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    
    
}
?>

</body>
</html>