<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	
	class maxPersonIDService{
		public function getMaxPersonID()
		{
			$sqltool = new SqlTool();
			$sql = "select * from MaxPersonID";
			$res = $sqltool->execute_dql2($sql);
			return $res;
			$sqltool->finish();
		}

		function updateMaxPersonID($id)
		{
			$sqltool = new SqlTool();
			$sql = "update MaxPersonID set id='$id'";
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