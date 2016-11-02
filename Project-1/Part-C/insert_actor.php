<?php 
	$isActor = $_POST['Aidentity'];
	$Afname = $_POST['AFname'];
	$Alname = $_POST['ALname'];
	$Asex = $_POST['ASex'];
	$Adob = $_POST['ADoB'];
	$Adod = $_POST['ADoD'];
	$actorPageNow = $_POST['page'];

	require 'actorService.class.php';
	require 'directorService.class.php';
	require 'config.php';
	//echo "$Sno $Sname $Sage $Sdept";
	$actorService = new actorService();
	$directorService = new directorService();
	//echo $b;
	//echo "12";
	//echo $Adob,$Adod;
	if($isActor=='true')
	{

		if($actorService->insertActor($Alname,$Afname,$Asex,$Adob,$Adod))
		{
			header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=8#foot");
			exit();
		}
		else {
			header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=4#foot");
			exit();
		}
	}
	else {

		if($directorService->insertDirector($Alname,$Afname,$Asex,$Adob,$Adod))
		{
			header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=8#foot");
			exit();
		}
		else {
			header("Location:index.php?actorPageNow=$actorPageNow&Type=2&msg=4#foot");
			exit();
		}
	}
	
	
?>