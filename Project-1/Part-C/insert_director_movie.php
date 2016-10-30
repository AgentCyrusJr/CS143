<?php 
	$mid = $_POST['MDMovie'];
	$did = $_POST['MDDirector'];
	
	require 'directormovieService.class.php';
	require 'config.php';
	
	//echo "$Sno $Sname $Sage $Sdept";
	$directormovieService = new directormovieService();
	//echo $b;
	//echo "12";
	//echo $Adob,$Adod;
	//print_r($_POST);
	//echo $mid,$aid,$role;
	//exit();
	if($directormovieService->insertMovieDirector($mid,$did))
	{
		header("Location:index.php?Type=5&msg=9#foot");
		exit();
	}
	else {
		header("Location:index.php?Type=5&msg=10#foot");
		exit();
	}
	
	
?>