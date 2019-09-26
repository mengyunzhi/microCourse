<?php

namespace app\index\widget;

use think\Controller;
use app\index\model\Menu;

class MenuWidget extends Controller {

	public function index() {
		$menus = [];
		array_push($menus, new Menu('page', '首页'));
		array_push($menus, new Menu('term', '学期控制'));
		array_push($menus, new Menu('teacher', '教师管理'));
		array_push($menus, new Menu('klass', '班级管理'));
		array_push($menus, new Menu('classroom', '教室管理'));
		array_push($menus, new Menu('test', '测试'));

		$this->assign('menus', $menus);

		return $this->fetch('widget/menu-widget/index');
	}
}