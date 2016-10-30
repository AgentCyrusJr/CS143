<?php 
	$mid = $_POST['MAMovie'];
	$aid = $_POST['MAActor'];
	$role = $_POST['MARole'];
	
	require 'actormovieService.class.php';
	require 'config.php';
	
	//echo "$Sno $Sname $Sage $Sdept";
	$actormovieService = new actormovieService();
	//echo $b;
	//echo "12";
	//echo $Adob,$Adod;
	//print_r($_POST);
	//echo $mid,$aid,$role;
	//exit();
	if($actormovieService->insertActorMovie($mid,$aid,$role))
	{
		header("Location:index.php?Type=4&msg=9#foot");
		exit();
	}
	else {
		header("Location:index.php?Type=4&msg=10#foot");
		exit();
	}
	
	
?>