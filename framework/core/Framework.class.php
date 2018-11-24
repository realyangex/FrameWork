<?php
//定义核心启动类
    class Framework{
     	//定义启动方法
     	public static function run()
      { 

     		self::init();
     		self::autoload();
     		self::router();
     		
     	}
      //定义初始化方法、
      public function init(){
        // 设置时区
        date_default_timezone_set("PRC");

      	define("DS", DIRECTORY_SEPARATOR);
     		define("ROOT", getcwd().DS);       //定义根目录
     		define('APP_PATH', ROOT.'application'.DS);
   		  define('FRAMEWORK_PATH', ROOT.'framework'.DS);
   		  define('PUBLIC_PATH', ROOT.'public'.DS);
     		define('CONFIG_PATH', APP_PATH.'config'.DS);
     		define('CONTROLLERS_PATH', APP_PATH.'controllers'.DS);
     		define('MODELS_PATH', APP_PATH.'models'.DS);
     		define('VIEWS_PATH', APP_PATH.'views'.DS);
     		define('CORE_PATH',FRAMEWORK_PATH.'core'.DS);
     		define('DATABASE_PATH',FRAMEWORK_PATH.'database'.DS);
     		define('HELPERS_PATH',FRAMEWORK_PATH.'helpers'.DS);
     		define('LIBS_PATH',FRAMEWORK_PATH.'libs'.DS);
     		

     		//手动加载核心控制器
     		include CORE_PATH.'Controller.class.php';
     		include CORE_PATH.'Model.class.php';
        include DATABASE_PATH.'Mysql.class.php';
     		include CORE_PATH.'coreFun.php';
     		$GLOBALS["config"] = include CONFIG_PATH.'config.php';
        // echo $GLOBALS["config"]["captcha"]["isOpen"];
        $captchaIsOpen = isset($GLOBALS["config"]["captcha"]["isOpen"])?$GLOBALS["config"]["captcha"]["isOpen"]:false;
        $upLoadFileIsOpen = isset($GLOBALS["config"]["upload"]["isOpen"])?$GLOBALS["config"]["upload"]["isOpen"]:false;

        // 判断是否开启验证码
        if($captchaIsOpen)
        { 
          include LIBS_PATH.'ValidateCode.class.php';
        }
        // 判断是否开启上传文件
        if($upLoadFileIsOpen)
        { 
          include LIBS_PATH.'UploadFile.class.php';
        }

        //前后台控制器和视图的目录
        $requestString = $_SERVER['QUERY_STRING'];
        // echo $requestString."<br>";
        $url = explode("/", $requestString);

        $actions = isset($url['3'])?$url['3']:'index';
        $action = explode("&", $actions)[0];
        $action =  $action!=""?$action:"index";

        $controller = isset($url['1'])?$url['1']:'index';
        $controller =  $controller!=""?$controller:"index";

        $module = isset($url['1'])?$url['1']:'index';
        $module =  $module!=""?$module:"index";

        define('PALTFORM', $module);
        define('CONTROLLER', $controller);
        define('ACTION',$action);
        define('CUR_CONTROLLER_PATH', CONTROLLERS_PATH.PALTFORM.DS);
        define('CUR_VIEW_PATH', VIEWS_PATH.PALTFORM.DS);
        // echo PALTFORM."->".CONTROLLER."->".ACTION."<br>";

         $GLOBALS["info"]["cur_module"] = PALTFORM;
         $GLOBALS["info"]["cur_controller"] = CONTROLLER;
         $GLOBALS["info"]["cur_action"] = ACTION;

         //如果不是调试模式不显示错误
         if(!$GLOBALS["config"]["base"]["debug"])
         {
            // 不显示错误
            error_reporting(~E_ALL);
         }
         


      }
      //定义路由方法
      public function router()
      {
        //确定类名和方法名
      	$controller_name = CONTROLLER."Controller";//如GoodsController
      	$action_name = ACTION;            //如addAction
      	

        //是否是debug模式
         if($GLOBALS["config"]["base"]["debug"])
         {
            if(!file_exists(CONTROLLERS_PATH.PALTFORM.""))
            { 
              echo "不存在".PALTFORM."模块</br>";
              d($GLOBALS["info"]);
              exit;
            }
            if(!file_exists(CUR_CONTROLLER_PATH.CONTROLLER."Controller.class.php"))
            { 
              echo "不存在".CONTROLLER."控制器</br>";
              d($GLOBALS["info"]);
              exit;
            }

            $controller = new $controller_name();
            $cls_methods = get_class_methods($controller);
            if(!in_array($action_name,$cls_methods))
            {
              echo "不存在".$action_name."方法</br>";
              d($GLOBALS["info"]);
              exit;
            }
         }else
         {
            if(!file_exists(CONTROLLERS_PATH.PALTFORM.""))
            { 
              echo "<center><h2>Sorry! Not Found</h2><hr>server@Apache</center>";
              exit;
            }
            if(!file_exists(CUR_CONTROLLER_PATH.CONTROLLER."Controller.class.php"))
            { 
              echo "<center><h2>Sorry! Not Found</h2><hr>server@Apache</center>";
              exit;
            }

            $controller = new $controller_name();
            $cls_methods = get_class_methods($controller);
            if(!in_array($action_name,$cls_methods))
            {
              echo "<center><h2>Sorry! Not Found</h2><hr>server@Apache</center>";
              exit;
            }
         }

        //实例化控制器，然后调用相应的方法
        
      	$controller->$action_name();

      }
      //定义自动加载方法
      public function autoload(){
      	spl_autoload_register(array(__CLASS__,'load'));
      }
      //加载方法
      public static function load($classname)
      {
      	//只负责加载 控制器类和模型类 的类 如GoodController AdminModel
        if(substr($classname, -10)=='Controller')
        {
          require CUR_CONTROLLER_PATH."{$classname}.class.php";
        }elseif(substr($classname, -5)== 'Model')
        {
          require MODELS_PATH."{$classname}.class.php";
        }
        else
        {
          //暂无其他
        }
      }

   }


?>