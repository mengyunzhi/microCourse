<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Admin;
/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 10:46:39
 */
class AIndexController extends Controller
{

	// 管理员操作验证————赵凯强
	public function __construct()
	{
		// 调用父类构造函数
		parent::__construct();

		// 验证用户是否登录
		if (!Admin::isLogin()) {
				return $this->error('管理员未登录', url('Login/index'));
		}
	}

	public function index()
	{

	}
}