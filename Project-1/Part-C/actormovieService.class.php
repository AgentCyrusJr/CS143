<?php
	require_once 'SqlTool.class.php';
	require_once 'DivideTool.class.php';
	
	class actorMovieService{
		public function insertActorMovie($mid,$aid,$role)
		{
			$sqltool = new SqlTool();
			$sql = "insert into MovieActor(mid,aid,role) values($mid,$aid,'$role')";
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