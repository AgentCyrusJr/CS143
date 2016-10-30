<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	//require 'config.php';
	//业务逻辑类 主要完成对userlist表的操作
	class directorService{
	
		
		function getAllDirectors()
		{
			$sql = "select id,first,last from Director";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}
		//插入新用户数据
		function insertDirector($last,$first,$sex,$dob,$dod)
		{
			$sql = "select max(id) from Director";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			//print_r($res);
			$aid = $res[0]['max(id)']+1;
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
		
		//获取userlist的总页数
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
		//获取对应分页的雇员信息
		function getUserlistByPage($pageNow,$pageSize){
			$sql = "select * from Actor limit ".($pageNow-1)*$pageSize.",$pageSize";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}
		
		//封装的方式完成分页
		
		function getDivideTool($pageNow,$pageSize,$numOfNow)
		{
			$dividetool = new DivideTool();
			$sql = "select count(id) from Actor";
			$sqltool = new SqlTool();
			$res = $sqltool->execute_dql2($sql);
		//	print_r($res);
			if($row = $res[0])
			{
				//获取总行数
				$rowC = $row['count(id)'];
				//print_r($row);
				//获取总页数
				$pageCount=ceil($row['count(id)']/$pageSize);
				
			}
			//获取结果
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