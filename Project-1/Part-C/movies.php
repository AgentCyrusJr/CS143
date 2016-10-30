 <div class="container_760">
 
 

<?php
	require_once("movieService.class.php");
	require_once("config.php");
	require_once("DivideTool.class.php");
	require_once("SqlTool.class.php");
	//当前页〉
	$pageNow = 1;
	
	if(!empty($_GET['moviePageNow']))
	{
		$pageNow = $_GET['moviePageNow'];
	}
	
	
	
	$movieservice = new movieService();
	$dividetool = $movieservice->getDivideTool($pageNow,$movies_list_pageSize,$movies_list_eachtime_page_count);
	
	if(empty($_GET['Mtype']))
	{
		$res = $dividetool->getRes();
	}

	
	//print_r($res);
	?>
 
    
    
    
    <?php
	echo "<p class=\"Actortitle\">Movies</p><hr/>";
	?>
	<a class="btn btn-success add_actor" href="#insertM" data-toggle='modal'>Add Movies</a>
	<form action="index.php?Type=1" class="searchForm" method="post">
	<div class="input-append">
	<input type="hidden" value="on" name="searchmovie"/>
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
			echo "<tr><th>ID</th><th>title</th><th>year</th><th>rating</th><th>company</th><th>delete</th></tr>";
			//寰幆鏄剧ず
			//print_r($res);
			for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				if(empty($row['dod'])){
					$row['dod'] = "N/A";
				}
				echo "<tr><td>{$row['id']}</td><td><a href=\"actorInfo.php?id={$row['title']}\">{$row['title']}</a></td><td>{$row['year']}</td><td>{$row['rating']}</td><td>{$row['company']}</td>"
				."<td><a onclick='return confirmDele({$row['id']})' href='delete_movie.php?Mid={$row['id']}&page={$pageNow}'>delete</a></td></tr>";
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
			echo "<a href='index.php?moviePageNow=$prePage&Type=3#foot'>pre</a>  ";
		}
	
		$nav = $dividetool->getNavigator();
		//print_r($nav);
		for($i=0;$i<count($nav);$i++)
		{
			if($nav[$i]!=$pageNow)
			echo "<a href='index.php?moviePageNow=$nav[$i]&Type=3#foot'>$nav[$i] </a>";
			else
			echo "$nav[$i] ";
		}
		
	
	
		if($pageNow<$dividetool->getPageCount())
		{
			$prePage = $pageNow+1;
			echo "  <a href='index.php?moviePageNow=$prePage&Type=3#foot'>next</a>";
		}
		
		
		echo "<br/>";
		echo " $pageNow / ".$dividetool->getPageCount()." <br/>   total ".$dividetool->getCount()." records";
		echo '<br/br/>';
		
    }
    else
    {
    	echo "<a href='index.php?moviePageNow=1&Type=3#foot'>return </a>";
    }
	
?>

<div class="modal hide fade" id="insertM">
<div class="modal-headeer">
<a href="#" class="close" data-dismiss="modal">×</a>
<h5 class="dialog_title">Add New Movie</h5></div>
<div class="modal-body">

<form class="form-horizontal" action="insert_movie.php" method="post" id="Mdialogform">
	
    <label class="dialog_label">Title
	<input type="text" class="update_input" name="MTitle" ></label>
    <label class="dialog_label">Company
	<input type="text" class="update_input" name="MCompany" ></label>
	<label class="dialog_label">Year
	<input type="date" class="update_input" name="MYear" placeholder="YYYY-MM-DD"></label>
	<p>ie: 1970-01-01</p>


    <div class="form-group">
	<label class="dialog_label">MPAA Rating:
        <select   class="form-control" name="MRate">
            <option value="G">G</option>
            <option value="NC-17">NC-17</option>
            <option value="PG">PG</option>
            <option value="PG-13">PG-13</option>
            <option value="R">R</option>
            <option value="surrendere">surrendere</option>
        </select>
	</label>
    </div>
    <div class="form-group">
	<label class="dialog_label">Movie Genre:
        <select  class="form-control" name="MGenre">
            <option value="Action">Action</option>
            <option value="Adult">Adult</option>
            <option value="Adventure">Adventure</option>
            <option value="Animation">Animation</option>
            <option value="Comedy">Comedy</option>
            <option value="Crime">Crime</option>
            <option value="Documentary">Documentary</option>
            <option value="Drama">Drama</option>
            <option value="Family">Family</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Horror">Horror</option>
            <option value="Musical">Musical</option>
            <option value="Mystery">Mystery</option>
            <option value="Romance">Romance</option>
            <option value="Sci-Fi">Sci-Fi</option>
            <option value="Short">Short</option>
            <option value="Thriller">Thriller</option>
            <option value="War">War</option>
            <option value="Western">Western</option>
        </select>   
   </label>
    </div>


<input type="submit" name = "submit" class="dialog_label" value="add"/>
<input type="hidden" value="<?php echo $pageNow;?>" name="page"/>
 </form>
</div>
 <div class="modal-footer">

 </div>

</div>

 
 
 </div>
 </div>