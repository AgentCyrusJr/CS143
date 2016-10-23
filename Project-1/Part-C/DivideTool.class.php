<?php

	require_once 'config.php';
	class DivideTool{
		public $pageCount;	//����õ�ҳ������
		public $res_array;  //���صĽ��
		public $rowCount;  //��ݿ��ѯ�������
		//public $pageNow;	//��ǰҳ�棬���ⲿ����
		public $navigator; //���ص�����
		public $pageSize;	//�ⲿ����ÿҳ�������
		public $count;
		
		/**
	 * @return the $count
	 */
	public function getCount() {
		return $this->count;
	}

		/**
	 * @param field_type $count
	 */
	public function setCount($count) {
		$this->count = $count;
	}

		function setProperties($pageC,$rowC,$res,$nav,$pageS,$count)
		{
			$this->pageCount = $pageC;
			$this->rowCount = $rowC;
			$this->res_array = $res;
			$this->navigator = $nav;
			$this->pageSize = $pageS;
			$this->count = $count;
		}
		
		function setRowCount($count)
		{
			$this->rowCount = $count;
		}
		function getRowCount()
		{
			return $this->rowCount;
		}
		
		
		
		function getPageSize()
		{
			return $this->pageSize;
		}
		function setPageSize($pagesize)
		{
			$this->pageSize=$pagesize;
		}
		
		function setRes($res)
		{
			$this->res_array = $res;
		}
		function getRes()
		{
			return $this->res_array;
		}
		
		function setPageCount($count)
		{
			$this->pageCount = $count;
		}
		function getPageCount()
		{
			return $this->pageCount;
		}
		
		function setNavigator($nav)
		{
			$this->navigator = $nav;
		}
		function getNavigator()
		{
			return $this->navigator;
		}
		
	}

?>