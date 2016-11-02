<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	require_once 'maxpersonIDService.class.php';
	//require 'config.php';
	
	class actorService{
	
		public function deleteActorByAid($aid)
		{
			$sqltool = new SqlTool();
			$sql = "delete from Actor where id=$aid";
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
	
		

		function isExist($aid)
		{
			$sql = "select * from Actor where id=$aid";
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

		function getAllActors()
		{
			$sql = "select id,first,last from Actor";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}

		function insertActor($last,$first,$sex,$dob,$dod)
		{
			$maxpersonidService = new maxPersonIDService();
			$res = $maxpersonidService->getMaxPersonID();
			//print_r($res);
			//print_r($res);
			$aid = $res[0]['id']+1;
			$sqltool = new SqlTool();
			if(empty($dod)){
				$sql = "insert into Actor(id,last,first,sex,dob) values($aid,'$last','$first','$sex','$dob')";
			} else{
				$sql = "insert into Actor(id,last,first,sex,dob,dod) values($aid,'$last','$first','$sex','$dob','$dod')";
			//echo $sql;
			}
			//exit();
			$r = $sqltool->execute_dml($sql);
			$sqltool->finish();
			if($r==1)
			{
				$maxpersonidService->updateMaxPersonID($aid);
				return true;
			}
			else
			{
				return false;
			}
			
		}
		function updateActor($aid,$last,$first,$sex,$dob,$dod)
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
			$sql = "select count(id) from Actor";
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
			$sql = "select * from Actor limit ".($pageNow-1)*$pageSize.",$pageSize";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}
	
		
		function getDivideTool($pageNow,$pageSize,$numOfNow)
		{
			$dividetool = new DivideTool();
			$sql = "select count(id) from Actor";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
		//	print_r($res);
			if($row = $res[0])
			{
				
				$rowC = $row['count(id)'];
				
				$pageCount=ceil($row['count(id)']/$pageSize);
				
			}
		
			$sql = "select * from Actor limit ".($pageNow-1)*$pageSize.",$pageSize";
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