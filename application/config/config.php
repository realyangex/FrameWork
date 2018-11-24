<?php

    //配置文件
    return array
    (

    	//基本设置
    	"base"=>array
    	(
    		"debug"=>true, //开启调试模式


    	),

    	// 数据库
    	"database"=>array
    	(
    		"host"=>"localhost",
	    	"user"=>"root",
	    	"password"=>"150861",
	    	"dbname"=>"frame",
	    	"port"=>"3306",
	    	"charset"=>"utf8"
    	),

    	// 验证码
    	"captcha"=>array
    	(
    		"isOpen" => true,//是否开启默认不开启
    		"name"=>"captcha",//存于session
    		"charset"=>"abcdefghkmnprstuvwxyz23456789",//随机因子
 			"codelen" => 5,//验证码长度
 			"width" => 150,//宽度
 			"height" => 50,//高度
 			"fontsize" => 20,//指定字体大小
    	),
    	

    	// 上传文件
    	"upload"=>array
    	(
    		"isOpen" => true,//是否开启默认不开启
    		"uplaodPath"=>"public/upload",//上传文件的路径
 			"type" => ['image/gif','image/jpeg','image/pjpeg','image/png' ],//允许上传的文件
 			'max_length'=> 1024*1024*10
    	)

    );
