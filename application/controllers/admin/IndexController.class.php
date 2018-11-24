<?php
//后台首页
	class IndexController extends Controller{

		public function indexAction(){
			// echo 'admin...index...';
			include CUR_VIEW_PATH.'index.html';
		}

		public function topAction(){
			include CUR_VIEW_PATH.'top.html';
		}
		public function menuAction(){
			include CUR_VIEW_PATH.'menu.html';
		}
		public function dragAction(){
			include CUR_VIEW_PATH.'drag.html';
		}
		public function mainAction(){
			// include CUR_VIEW_PATH.'main.html';
			$this->helper("input");
			test();
			$adminModel = new AdminModel('classmate');
			$addmin = $adminModel->test();
			echo '<pre>';
			var_dump($addmin);
			echo '<pre>';
		}
	}


?>