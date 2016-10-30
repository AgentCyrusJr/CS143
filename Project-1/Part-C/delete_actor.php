<?php 
	$Aid = $_GET['Aid'];
	$actorPageNow = $_GET['page'];
	require 'actorService.class.php';
	require 'config.php';
	
	
	$actorService = new actorService();
	$b = $actorService->isExist($Aid);
	if(!$b)
	{
		header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=2#foot");
		exit();
	}
	if($actorService->deleteActorByAid($Aid))
	{
		
		header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=2#foot");
	
		exit();
	}
	else {
		header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=5#foot");
		exit();
	}
	
	
?>