<?php
   //前台展示页面
   class IndexController{
   	public function index()
   	{
   		$con = getCon();
   		$smarty = getSmarty();
   		d($con->all('user'));
   		$smarty->assign("test","帅哥");
   		$smarty->display(CUR_VIEW_PATH."index.html");
   	}
   	public function insert()
   	{
   		$con = getCon();
   		$data = array("username"=>"杨琛","password"=>"yangc");
   		// echo $con->insert('user',$data);
   	}

   	public function update()
   	{
   		$con = getCon();
   		$data = array("username"=>"杨琛","password"=>"123456");
   		// echo $con->update('user',"where 1=1",$data);
   	}

   	public function delete()
   	{
   		$con = getCon();
   		// echo $con->delete('user',"where id=3 or id=4");
   	}

   	public function query()
   	{
   		$con = getCon();
   		// d($con->query("update user set password='888' where id =3"));
   		// d($con->query("select * from user"));
   		session("aa","");
		d(session("aa")); 		
   	}
   	public function img(){
   		captcha();
   	}
   	public function upload(){
   		d(upload());
   	}
   }

?>