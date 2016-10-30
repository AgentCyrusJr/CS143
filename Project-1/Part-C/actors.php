 <div class="container_760">
 
 

<?php
	require_once("actorService.class.php");
	require_once("config.php");
	require_once("DivideTool.class.php");
	require_once("SqlTool.class.php");
	//当前页〉
	$pageNow = 1;
	
	if(!empty($_GET['actorPageNow']))
	{
		$pageNow = $_GET['actorPageNow'];
	}
	
	
	
	$actorservice = new actorService();
	$dividetool = $actorservice->getDivideTool($pageNow,$actors_list_pageSize,$actors_list_eachtime_page_count);
	
	if(empty($_GET['Atype']))
	{
		$res = $dividetool->getRes();
	}
	
	//print_r($res);
	?>
 
    
    
    
    <?php
	echo "<p class=\"Actortitle\">Actors</p><hr/>";
	?>
	<a class="btn btn-success add_actor" href="#insert" data-toggle='modal'>Add Actor/Director</a>
	<form action="index.php?Type=1" class="searchForm" method="post">
	<div class="input-append">
	<input type="hidden" value="on" name="searchactor"/>
	<input type="text" name="search"><input type="submit" class="btn" value="search">
	</div>
	</form>
	<?php
	if(empty($res))
	{
		echo "<hr/><p>no results</p>";
	}
	else 
	{
			echo "<table class=\"Actortable table table-striped\">";
			echo "<tr><th>ID</th><th>name</th><th>sex</th><th>dob</th><th>dod</th><th>delete</th></tr>";
			//寰幆鏄剧ず
			//print_r($res);
			for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				if(empty($row['dod'])){
					$row['dod'] = "N/A";
				}
				echo "<tr><td>{$row['id']}</td><td><a href=\"actorInfo.php?id={$row['id']}\">{$row['last']} {$row['first']}</a></td><td>{$row['sex']}</td><td>{$row['dob']}</td><td>{$row['dod']}</td>"
				."<td><a onclick='return confirmDele({$row['id']})' href='delete_actor.php?Aid={$row['id']}&page={$pageNow}'>delete</a></td></tr>";
			}
			echo "</table>";
	}
	//鎵撳嵃椤电爜鐨勮秴閾炬帴
	/*for($i=1;$i<=$pageCount;$i++)
	{
		echo "<a href='userList.php?pageNow=$i'>$i</a>  ";
	}*/
	?>
    <div class="divide_div">
    <?php
    if(empty($_GET['Atype']))
    {
		if($pageNow>1)
		{
			$prePage = $pageNow-1;
			echo "<a href='index.php?actorPageNow=$prePage&Type=2#foot'>pre</a>  ";
		}
	
		$nav = $dividetool->getNavigator();
		//print_r($nav);
		for($i=0;$i<count($nav);$i++)
		{
			if($nav[$i]!=$pageNow)
			echo "<a href='index.php?actorPageNow=$nav[$i]&Type=2#foot'>$nav[$i] </a>";
			else
			echo "$nav[$i] ";
		}
		
	
	
		if($pageNow<$dividetool->getPageCount())
		{
			$prePage = $pageNow+1;
			echo "  <a href='index.php?actorPageNow=$prePage&Type=2#foot'>next</a>";
		}
		
		
		echo "<br/>";
		echo " $pageNow / ".$dividetool->getPageCount()." <br/>   total ".$dividetool->getCount()." records";
		echo '<br/br/>';
		
    }
    else
    {
    	echo "<a href='index.php?actorPageNow=1&Type=2#foot'>return </a>";
    }
	
?>

<div class="modal hide fade" id="insert">
<div class="modal-headeer">
<a href="#" class="close" data-dismiss="modal">×</a>
<h5 class="dialog_title">Add New Actor/Director</h5></div>
<div class="modal-body">

<form class="form-horizontal" action="insert_actor.php" method="post" id="dialogform">
	
<div class="form-group">

        <input type="radio" checked="checked" name="Aidentity" value="true"/>Actor
        <input type="radio" name="Aidentity" value="false"/>Director
</div>
    <label class="dialog_label">First Name
	<input type="text" class="update_input" name="AFname" ></label>

	<label class="dialog_label">Last Name
	<input type="text" class="update_input" name="ALname" ></label>


	<div class="form-group">
        <input type="radio" class="radio-inline" checked="checked" name="ASex" value="Male"/>Male
        <input type="radio" class="radio-inline" name="Asex" value="Female"/>Female
	</div>

	<label class="dialog_label">Date of Birth
	<input type="date" class="update_input" name="ADoB" placeholder="YYYY-MM-DD"></label>
	<p>ie: 1970-01-01</p>
	<label class="dialog_label">Date of Death
	<input type="date" class="update_input" name="ADoD" placeholder="YYYY-MM-DD"></label>
	<p>(if alive, just leave blank)</p>

<input type="submit" name = "submit" class="dialog_label" value="add"/>
<input type="hidden" value="<?php echo $pageNow;?>" name="page"/>
 </form>
</div>
 <div class="modal-footer">

 </div>

</div>

 
 
 </div>
 </div>