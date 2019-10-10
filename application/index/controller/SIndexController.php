<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Student;  // 引入学生
use think\facade\Session;
use app\index\model\Term;


/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-28 16:52:17
 */
class SIndexController extends Controller
{

	// 学生操作验证————赵凯强
	public function __construct()
	{
		// 调用父类构造函数
		parent::__construct();
		// 判断是否有学期
		if(Term::NoTerm()) {
            // return $this->fetch('Login/noterm');
            return $this->error('系统尚未初始化', url('Login/noterm'));
        }
		// 验证用户是否登录
		if (!Student::isLogin()) {
			return $this->error('学生未登录', url('Login/index'));
		}


		$id = Session::get('studentId');
		if (Student::where('id',$id)->find()->name === Null){
			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/judgeRole?id='.$id);
		}
	}

	public function index()
	{
          
	}
}