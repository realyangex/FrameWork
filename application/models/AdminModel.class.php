<?php

  //AdminModel 模型
  class AdminModel extends Model{
  	public function test(){
  		$sql = "select * from {$this->table}";
  		return $this->db->getAll($sql);
  	}

  }