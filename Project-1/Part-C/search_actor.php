<?php 
	$Ainfo = $_POST['search_actor'];
	
	require 'actorService.class.php';
	require 'config.php';
	
	if(is_numeric($Ainfo))
	{
		if(strlen($Ainfo)==10)
			header("Location:index.php?Type=2&Atype=id&Ainfo=$Ainfo#foot");
		else
			header("Location:index.php?Type=2&Atype=Sage&Ainfo=$Ainfo#foot");
			
	}
	else {
		header("Location:index.php?Type=2&Atype=text&Ainfo=$Ainfo#foot");
	}
	exit();
	
		
	
	
	
?>