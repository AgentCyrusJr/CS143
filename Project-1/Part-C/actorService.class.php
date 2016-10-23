<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	//require 'config.php';
	//业务逻辑类 主要完成对userlist表的操作
	class actorService{
	
		//根据id删除用户
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
	
		
		//验证用户是否存在
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
		//插入新用户数据
		function insertActor($aid,$last,$first,$sex,$dob,$dod)
		{
			$sqltool = new SqlTool();
			$sql = "insert into Actor(id,last,first,sex,dob,dod) values($aid,'$last','$first','$sex',$dob,$dod)";
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
			$sql = "update Actor set last='$last',first='$first',sex='$sex',dob=$dob,dod='$dod' where id=$aid";
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