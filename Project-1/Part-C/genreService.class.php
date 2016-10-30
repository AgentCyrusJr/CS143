<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	//require 'config.php';
	//业务逻辑类 主要完成对userlist表的操作
	class genreService{
		public function deleteGenreByMid($mid)
		{
			$sqltool = new SqlTool();
			$sql = "delete from MovieGenre where mid=$mid";
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
		function isExist($mid)
		{
			$sql = "select * from MovieGenre where mid=$mid";
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
		function insertGenre($mid,$genre)
		{
			$sqltool = new SqlTool();
			$sql = "insert into MovieGenre(mid,genre) values($mid,'$genre')";
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

		
	}
	
	
	
?>