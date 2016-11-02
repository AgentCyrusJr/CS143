 <div class="container_760">
 
 

<?php
	require_once("movieService.class.php");
	require_once("config.php");
	require_once("DivideTool.class.php");
	require_once("SqlTool.class.php");

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
		
			for($i=0;$i<count($res);$i++)
			{
				$row = $res[$i];
				if(empty($row['dod'])){
					$row['dod'] = "N/A";
				}
				echo "<tr><td>{$row['id']}</td><td><a href=\"show_movie.php?mid={$row['id']}\">{$row['title']}</a></td><td>{$row['year']}</td><td>{$row['rating']}</td><td>{$row['company']}</td>"
				."<td><a onclick='return confirmDele({$row['id']})' href='delete_movie.php?Mid={$row['id']}&page={$pageNow}'>delete</a></td></tr>";
			}
			echo "</table>";
	}

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
        	<input type="checkbox" name="genre[]" value="Action">Action</input>
        	<input type="checkbox" name="genre[]" value="Adult">Adult</input>
			<input type="checkbox" name="genre[]" value="Adventure">Adventure</input>

			<input type="checkbox" name="genre[]" value="Animation">Animation</input>

			<input type="checkbox" name="genre[]" value="Comedy">Comedy</input>

			<input type="checkbox" name="genre[]" value="Crime">Crime</input>

			<input type="checkbox" name="genre[]" value="Documentary">Documentary</input>
			<input type="checkbox" name="genre[]" value="Drama">Drama</input>

			<input type="checkbox" name="genre[]" value="Family">Family</input>
			<input type="checkbox" name="genre[]" value="Fantasy">Fantasy</input>
			<input type="checkbox" name="genre[]" value="Horror">Horror</input>
			<input type="checkbox" name="genre[]" value="Musical">Musical</input>
			<input type="checkbox" name="genre[]" value="Mystery">Mystery</input>
			<input type="checkbox" name="genre[]" value="Romance">Romance</input>
			<input type="checkbox" name="genre[]" value="Sci-Fi">Sci-Fi</input>
			<input type="checkbox" name="genre[]" value="Short">Short</input>
			<input type="checkbox" name="genre[]" value="Thriller">Thriller</input>
			<input type="checkbox" name="genre[]" value="War">War</input>
			<input type="checkbox" name="genre[]" value="Western">Western</input>
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