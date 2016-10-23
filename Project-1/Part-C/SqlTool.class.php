<?php

	require_once 'config.php';
	class SqlTool
	{
		private $conn;
		private $host='localhost';
		private $user='cs143';
		private $password='';
		private $db='CS143';
		
		function SqlTool(){
			
			$this->conn=mysql_connect($this->host,$this->user,$this->password);
			if(!$this->conn){
				die("连接失败".mysql_error());
			}
			mysql_select_db($this->db,$this->conn) or die(mysql_errno());
			mysql_query("set names utf8");
		}
		
	
	
		//鏂规硶
	
		//1.dql
		public function execute_dql($sql){
			$res = mysql_query($sql,$this->conn) or die(mysql_error());
			return $res;
		}
		
		//2返回数组的dql方法
		public function execute_dql2($sql){
			$arr = array();
			$res = mysql_query($sql,$this->conn) or die(mysql_error());
			$i = 0;
			while($row = mysql_fetch_assoc($res))
			{
				$arr[$i++]=$row;
			}
			mysql_free_result($res);
			return $arr;
		}
		
		//分页查询方法
		public function execute_dql_divede($sql1,$sql2,&$dividetool)
		{
			
		}
		
		//2.update,delete , insert dml
		public function execute_dml($sql){
			$b=mysql_query($sql,$this->conn);
			if(!$b){
				return 0;
			}else{
				if(mysql_affected_rows($this->conn)>0){
					return 1;
				}
				else{
					return 2;
				}
			}
		}
		public function execute_dml2($sql){
			$b=mysql_query($sql,$this->conn);
			if(!$b){
				return 0;
			}else{
				return mysql_affected_rows($this->conn);
				
			}
		}
		public function finish()
		{
			if(!empty($this->conn))
			{
				mysql_close($this->conn);
			}		
		}
	}

?>