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
	else {
		$Sinfo = $_GET['Ainfo'];
		$sql = "select * from Actor where (id='$Ainfo') or (last='$Ainfo') or (first='$Ainfo') or (sex='$Ainfo')";
		$sqltool = new SqlTool();
		$res = $sqltool->execute_dql2($sql);
		$sqltool->finish();
	
		//select * from Actor where (id='$Ainfo') or (last='$Ainfo') or (first='$Ainfo') or (sex='$Ainfo')
	}
	
	//print_r($res);
	?>
 
    
    
    
    <?php
	echo "<p class=\"Actortitle\">Actors</p><hr/>";
	?>
	<a class="btn btn-success add_actor" href="#insert" data-toggle='modal'>Add Actors</a>
	<form action="search_actor.php" class="searchForm" method="post">
	<div class="input-append">
	<input type="text" name="search_actor"><input type="submit" class="btn" value="search">
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
			echo "<tr><th>ID</th><th>last</th><th>first</th><th>sex</th><th>dob</th><th>dod</th></tr>";
			//寰幆鏄剧ず
			//print_r($res);
			for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				echo "<tr><td>{$row['id']}</td><td>{$row['last']}</td><td>{$row['first']}</td><td>{$row['sex']}</td>"
				."<td><a href='#update' data-toggle='modal' onclick=\""."document.getElementById('Aid').value = {$row['id']};document.getElementById('Sname').value = '"."{$row['Sname']}"."';document.getElementById('Sage').value = {$row['Sage']};document.getElementById('Sdept').value = '"."{$row['Sdept']}"."';\"".">修改资料</a></td><td><a onclick='return confirmDele({$row['Sno']})' href='delete_stu.php?Sno={$row['Sno']}&page={$pageNow}'>删除学生</a></td></tr>";
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
    	echo "<a href='main.php?actorPageNow=1&Type=1#foot'>返回 </a>";
    }
	
?>

<div class="modal hide fade" id="update">
<div class="modal-headeer">
<a href="#" class="close" data-dismiss="modal">×</a>
<h5 class="dialog_title">学生信息修改</h5></div>
<div class="modal-body">

<form class="form-horizontal" action="update_stu.php" method="post" id="dialogform">



<label class="dialog_label" >学号

<input type="text" readonly="readonly" class="update_input" name="Sno" id="Sno"></label>

<label class="dialog_label">姓名

<input type="text" class="update_input" name="Sname" id="Sname"></label>
<label class="dialog_label">年龄

<input type="text" class="update_input" name="Sage" id="Sage"></label>
<label class="dialog_label">专业

<input type="text" class="update_input" name="Sdept" id="Sdept"></label>
<input type="submit" name = "submit" class="dialog_label" value="修改"/>
<input type="hidden" value="<?php echo $pageNow;?>" name="page"/>
 </form>
</div>
 <div class="modal-footer">

 </div>

</div>

<div class="modal hide fade" id="insert">
<div class="modal-headeer">
<a href="#" class="close" data-dismiss="modal">×</a>
<h5 class="dialog_title">添加学生信息</h5></div>
<div class="modal-body">

<form class="form-horizontal" action="insert_stu.php" method="post" id="dialogform">



<label class="dialog_label" >学号

<input type="text" class="update_input" name="Sno"></label>

<label class="dialog_label">姓名

<input type="text" class="update_input" name="Sname" ></label>
<label class="dialog_label">年龄

<input type="text" class="update_input" name="Sage" ></label>
<label class="dialog_label">专业

<input type="text" class="update_input" name="Sdept" ></label>
<input type="submit" name = "submit" class="dialog_label" value="添加"/>
<input type="hidden" value="<?php echo $pageNow;?>" name="page"/>
 </form>
</div>
 <div class="modal-footer">

 </div>

</div>

 
 
 </div>
 </div>