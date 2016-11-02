<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	
	class maxMovieIDService{
		public function getMaxMovieId()
		{
			$sqltool = new SqlTool();
			$sql = "select * from MaxMovieID";
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}

		function updateMaxMovieID($id)
		{
			$sqltool = new SqlTool();
			$sql = "update MaxMovieID set id='$id'";
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