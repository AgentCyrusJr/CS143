<?php 
	$Mid = $_GET['Mid'];
	$moviePageNow = $_GET['page'];
	require 'movieService.class.php';
	require 'config.php';
	
	
	$movieService = new movieService();
	$b = $movieService->isExist($Mid);
	if(!$b)
	{
		header("Location:index.php?moviePageNow=$moviePageNow&Type=3&msg=2#foot");
		exit();
	}
	if($movieService->deleteMovieByMid($Mid))
	{
		
		header("Location:index.php?moviePageNow=$moviePageNow&Type=3&msg=5#foot");
	
		exit();
	}
	else {
		header("Location:index.php?moviePageNow=$moviePageNow&Type=3&msg=5#foot");
		exit();
	}
	
	
?>