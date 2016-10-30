<?php 
	$Mtitle = $_POST['MTitle'];
	$Mcompany = $_POST['MCompany'];
	$Mrate = $_POST['MRate'];
	$Mgenre = $_POST['MGenre'];
	$Myear = $_POST['MYear'];
	$moviePageNow = $_POST['page'];
	require 'movieService.class.php';
	require 'config.php';
	
	//echo "$Sno $Sname $Sage $Sdept";
	$movieService = new movieService();
	//echo $b;
	//echo "12";
	//echo $Adob,$Adod;
	//echo $Mtitle,$Mcompany,$Mrate,$Mgenre,$Myear;
	//print_r($_POST);
	//exit();
	if($movieService->insertMovie($Mtitle,$Myear,$Mrate,$Mcompany,$Mgenre))
	{

		header("Location:index.php?moviePageNow=$moviePageNow&Type=2&msg=8#foot");
		exit();
	}
	else {
		header("Location:index.php?moviePageNow=$moviePageNow&Type=2&msg=4#foot");
		exit();
	}
	
	
?>