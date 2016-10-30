
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CS143 Query System</title>


<link rel="stylesheet" href="style/reset.css" type="text/css"/>
<link rel="stylesheet" href="style/text.css" type="text/css"/>
<link rel="stylesheet" href="style/bootstrap.css" type="text/css"/>
<link rel="stylesheet" href="style/page.css" type="text/css"/>
<link rel="stylesheet" href="style/960_16_col.css" type="text/css"/>


</head>



<body class="bg" onLoad="clickCarousel();">

<?php

require("Header.php");
if(empty($_GET['ActorPageNow']))
{
	$stuPageNow = 1;
}
else {
	$stuPageNow = $_GET['ActorPageNow'];
}
if(empty($_GET['CnoPageNow']))
{
	$CnoPageNow = 1;
}
else {
	$CnoPageNow = $_GET['CnoPageNow'];
}
if(empty($_GET['SCPageNow']))
{
	$SCPageNow = 1;
}
else {
	$SCPageNow = $_GET['SCPageNow'];
}
if(empty($_GET['Type']))
{
	$type = 1;
}
else {
	$type = $_GET['Type'];
}

?>

  <div class="container">
    <?php 
	require "index_carousel.php";
	?>
	</div>



<div class="container">
<div class="tabbable tabs-left">
	<ul class="nav nav-tabs">
		<li <?php if($type==1) echo "class='active'";?>><a href="#tab1" data-toggle="tab">Search</a></li>
    	<li <?php if($type==2) echo "class='active'";?>><a href="#tab2" data-toggle="tab">Actors</a></li>
        <li <?php if($type==3) echo "class='active'";?>><a href="#tab3" data-toggle="tab">Movies</a></li>
         <li <?php if($type==4) echo "class='active'";?>><a href="#tab4" data-toggle="tab">Add Movie/Actor</a></li>
        <li <?php if($type==5) echo "class='active'";?>><a href="#tab5" data-toggle="tab">Add Movie/Director</a></li>
         
    </ul>
    <div class="tab-content">
    	<div class="tab-pane <?php if($type==1) echo "active";?>" id="tab1">
			       <?php require "search_actor_movie.php";?>
        
        </div>
        <div class="tab-pane <?php if($type==2) echo "active";?>" id="tab2">
   			
				      <?php require "actors.php";?>
        </div>
        
        <div class="tab-pane <?php if($type==3) echo "active";?>" id="tab3">
       		
			        <?php require "movies.php";?>
      
   		 </div>
   		 <div class="tab-pane <?php if($type==4) echo "active";?>" id="tab4">
       		
			        <?php require "movieActor.php";?>
		</div>
		<div class="tab-pane <?php if($type==5) echo "active";?>" id="tab5">
       		
			        <?php require "movieDirector.php";?>
      
   		 </div>
	</div>


	</div>
    </div>
 

<div id="foot">

	<?php 

require("footer.php");
?>
</div>

<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
function confirmDele(val)
{
	return window.confirm("是否删除id为"+val+"的记录？");
}
function confirmDele1(val)
{
	return window.confirm("是否删除表名为"+val+"的表？");
}
</script>
<script type="text/javascript">
function clickCarousel()
{
	cl = document.getElementById("nextClick");
	cl.click();
	
	
}
</script>
</body>
</html>