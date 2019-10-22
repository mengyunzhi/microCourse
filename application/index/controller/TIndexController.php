<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Teacher;
use app\index\model\Student;
use think\facade\Session;
use app\index\model\Term;
/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-22 19:05:52
 */
class TIndexController extends Controller
{

	// 老师操作验证————赵凯强
	public function __construct()
	{
		// 调用父类构造函数
		parent::__construct();
		if(Term::NoTerm()) {
            // return $this->fetch('Login/noterm');
            return $this->error('系统尚未初始化', url('Login/noterm'));
        }
		// 验证教师是否登录
		if (!Teacher::isLogin()) {
			//如果教师未登录，判断学生是否登陆
			if (Student::isLogin()) {
				if (Student::where('id',session('studentId'))->find()->name == Null) {
                    //当学生ID数据为空时，不知道此用户时学生还是教师
                    //跳转到签到界面
                    $id = Session::get('studentId');
                    $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/judgeRole?id='.$id);
                }
				//如果学生是登陆状态，跳转到学生的控制器
				return $this->error('抱歉，您的身份是学生', url('Student/page'));
			}
			//如果学生没有登陆，跳转到登陆界面
			return $this->error('教师未登录', url('Login/index'));
		}

  		//$id = Session::get('teacherId');
  		////如果教师数据为空，则学生尚未注册，跳转到注册界面
		// if (Teacher::where('id',$id)->find()->name === Null){
		// 	$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Login/judgeRole?id='.$id);
		// }
	}
	public function index()
	{

	}
}