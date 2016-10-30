<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	//require 'config.php';
	//业务逻辑类 主要完成对userlist表的操作
	class directorMovieService{
		public function insertMovieDirector($mid,$did)
		{
			$sqltool = new SqlTool();
			$sql = "insert into MovieDirector (mid,did) values($mid,$did)";
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