<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Student;  // 引入学生



/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 10:46:39
 */
class SIndexController extends Controller
{

	// 学生操作验证————赵凯强
	public function __construct()
	{
		// 调用父类构造函数
		parent::__construct();

		// 验证用户是否登录
		if (!Student::isLogin()) {
				return $this->error('学生未登录', url('Login/index'));
		}
	}

	public function index()
	{
          
	}
}