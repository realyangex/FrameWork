<?php

	/**
	 *
	 * var_dump() 打印值
	 */
	function d($value)
	{	
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}
	/**
	 *
	 * 返回mysql操作对象
	 */
	function getCon()
	{
		return Mysql::con();
	}

	/**
	 *	获取参数
	 *
	 */
	function input($name="")
	{	
		$params = array();
		$params = array_merge($_GET,$_POST);
		if($name=="")
		{
			return $params;
		}else
		{
			return $params[$name];
		}
	}
	/**
	 *	session 管理
	 */
	function session($key="",$value="asdfghjklqwertyuiozxcvbnmgkildhrkgs")
	{	
		$check = "asdfghjklqwertyuiozxcvbnmgkildhrkgs";
		if(!isset($_SESSION))
		{
			session_start();
		}

		if($key!=""&&$value!=$check)
		{
			$_SESSION[$key] = $value;
		}
		if($key!=""&&$value==$check)
		{
			return @$_SESSION[$key];
		}
		if($key==""&&$value==$check)
		{
			return $_SESSION;
		}
		if($key!=""&&$value="")
		{
			$_SESSION[$key] = $value;
		}
	}

	/**
	 *	验证码
	 */
	function captcha()
	{	
		$validate = ValidateCode::getValidateCode();
		$validate->doimg();
	}

	/**
	 * 上传文件
	 */
	function upload(){
		$uploadFile = UploadFile::getUploadFile();
		// var_dump($uploadFile);
		return $uploadFile->upload();
	}

	/**
	 *	返回视图
	 */
	function  view($path)
	{ 
		$prePath = $GLOBALS["info"]["cur_module"]."/".$GLOBALS["info"]["cur_controller"];
		$path = $prePath."/".$path;
		$path  = VIEWS_PATH.$path;
		include $path;
	}

	function __call()
	{
		echo "<center><h2>Sorry! Not Found</h2><hr>server@Apache</center>";
	}

	/**
	 * 返回Smarty 对象
	 *
	 */
	function getSmarty(){
		require_once LIBS_PATH."smarty/Smarty.class.php";
		return new Smarty();
	}
?>