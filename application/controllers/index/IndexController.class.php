<?php
   //前台展示页面
   class IndexController{
   	public function index()
   	{
         echo "<h1 style='padding:0 0 0 20px'>welcome to miniPHPFrameWork</h1>";
         echo "<label style='padding:0 0 0 20px'>copyright@realyangex</label>";
   	}
   	public function insert()
   	{
   		$con = getCon();
   		$data = array("username"=>"test","password"=>"123456");
   		// echo $con->insert('user',$data);
   	}

   	public function update()
   	{
   		$con = getCon();
   		$data = array("username"=>"test","password"=>"123456");
   		// echo $con->update('user',"where 1=1",$data);
   	}

   	public function delete()
   	{
   		$con = getCon();
   		echo $con->delete('user',"where id=3 or id=4");
   	}

   	public function query()
   	{

   	}
   	public function img(){
   		captcha();
   	}
   	public function upload(){
   		d(upload());
   	}
   }

?>