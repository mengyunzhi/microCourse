<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Student; //引入学生
use app\index\model\Teacher; //引入教师
use think\facade\Session;
use app\index\model\Term;


/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-17 20:30:32
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
		// 验证学生是否登录
		if (!Student::isLogin()) {
			//如果学生未登录，验证教师是否登陆
			if (Teacher::isLogin()) {
				//如果教师登陆，说明当前用户是教师，跳转到教师控制器
				return $this->error('抱歉，您的身份是教师', url('Teacher/page'));
			}
			//如果学生和教师都没登陆，跳转到登陆界面
			return $this->error('学生未登录', url('Login/index'));
		}

		$id = Session::get('studentId');
		//如果学生数据为空，则学生尚未注册，跳转到注册界面
		if (Student::where('id',$id)->find()->name === Null){
			$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/judgeRole?id='.$id);
		}
	}

	public function index()
	{
          
	}
}