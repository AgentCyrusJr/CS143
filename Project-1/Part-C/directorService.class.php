<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	//require 'config.php';
	require_once 'maxpersonIDService.class.php';

	class directorService{
	
		
		function getAllDirectors()
		{
			$sql = "select id,first,last from Director";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}

		function insertDirector($last,$first,$sex,$dob,$dod)
		{
			$maxpersonidService = new maxPersonIDService();
			$res = $maxpersonidService->getMaxPersonID();
			//print_r($res);
			$aid = $res[0]['id']+1;
			$sqltool = new SqlTool();
			if(empty($dod)){
				$sql = "insert into Director(id,last,first,dob) values($aid,'$last','$first','$dob')";
			} else{
				$sql = "insert into Director(id,last,first,dob,dod) values($aid,'$last','$first','$dob','$dod')";
			//
			}
			//echo $sql;
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