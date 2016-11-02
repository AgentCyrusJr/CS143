<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	require_once 'genreService.class.php';
	require_once 'maxmovieIDService.class.php';

	class movieService{
	

		public function deleteMovieByMid($mid)
		{
			$sqltool = new SqlTool();
			$sql = "delete from Movie where id=$mid";
			if($sqltool->execute_dml($sql))
			{
				
					$sqltool->finish();
					return 1;
			}
			else
			{
				$sqltool->finish();
				return 0;
			}
		}
	

		function isExist($mid)
		{
			$sql = "select * from Movie where id=$mid";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			//print_r($res);
			if(empty($res))
			{
				return 0;
			}
			return 1;
			$sqltool->finish();
		}

		function insertMovie($title,$year,$rating,$company,$genre)
		{
			$genreService = new genreService();
			$maxmovieidService = new maxMovieIDService();
			$res = $maxmovieidService->getMaxMovieId();
			//print_r($res);
			$mid = $res[0]['id']+1;
			$sqltool = new SqlTool();
			$sql = "insert into Movie(id,title,year,rating,company) values($mid, '$title','$year','$rating','$company')";
			$r = $sqltool->execute_dml($sql);
			$sqltool->finish();
			if($r==1)
			{	
				$maxmovieidService->updateMaxMovieID($mid);
				if ($genreService->insertGenre($mid,$genre)){
					return true;
				}else{
					return false;
				}
			}
			else
			{
				return false;
			}
			
		}

		function getMaxMovie() {
			$sql = "select max(id) from Movie";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			//print_r($res);
			return $res[0]['max(id)'];
		}

		function updateMovie($aid,$last,$first,$sex,$dob,$dod)
		{
			$sqltool = new SqlTool();
			$sql = "update Actor set last='$last',first='$first',sex='$sex',dob='$dob',dod='$dod' where id=$aid";
			$r = $sqltool->execute_dml($sql);
			$sqltool->finish();
			if($r==1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function getPageCount($pageSize)
		{
			$sql = "select count(id) from Movie";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
		//	print_r($res);
			if($row = $res[0])
			{
				//print_r($row);
				$pageCount=ceil($row['count(id)']/$pageSize);
				
			}
			
			$sqltool->finish();
			
			return $pageCount;
		}

		function getUserlistByPage($pageNow,$pageSize){
			$sql = "select * from Movie limit ".($pageNow-1)*$pageSize.",$pageSize";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}
		function getAllMovies()
		{
			$sql = "select id,title from Movie";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}

		
		function getDivideTool($pageNow,$pageSize,$numOfNow)
		{
			$dividetool = new DivideTool();
			$sql = "select count(id) from Movie";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
		//	print_r($res);
			if($row = $res[0])
			{
			
				$rowC = $row['count(id)'];
				
				$pageCount=ceil($row['count(id)']/$pageSize);
				
			}
		
			$sql = "select * from Movie limit ".($pageNow-1)*$pageSize.",$pageSize";
			$res = $sqltool->execute_dql2($sql);
			//print_r($res);
			//$numOfNow = $user_list_eachtime_page_count;
			//print_r($user_list_eachtime_page_count);
			$limit = $pageNow+$numOfNow;
			$nav = array();
			//print_r($limit);
			$i=0;
			for($start=$pageNow-$numOfNow;$start<$limit;$start++)
			{
				if($start>0&&$start<$pageCount+1)
					$nav[$i++] = $start;
			}
			
			$dividetool->setProperties($pageCount,$rowC,$res,$nav,$pageSize,$rowC);
			$sqltool->finish();
			return $dividetool;
		}
		
	}
	
	
	
?>