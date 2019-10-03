<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Student;
use app\index\model\Klass;
use app\index\model\Teacher;
use app\index\model\Admin;
use app\index\model\College;
use app\index\validate\StudentValidate;

/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-28 12:59:20
 */
class LoginController extends Controller
{
	// 学生注册页面跳转————赵凯强——（已增加微信）
	public function register($id)
	{
		$klasses = Klass::all();
		$colleges = College::all();
		// $index = 0;
		// $colleges = [
		// ];
		// foreach ($klasses as $klass) {
		// 	# code...
		// 	$college = new College();
		// 	$college->id = $index ++;
		// 	$college->name = 'name' . $college->id;
		// 	$klass->collegeId = $college->id;
		// 	array_push($colleges, $college);
		// }
		// dump($klasses);
		// echo '<br>';
		// dump($colleges);
		// return ;
		$this->assign('id', $id);
		$this->assign('klasses', $klasses);
		$this->assign('colleges', $colleges);
		return $this->fetch();
	} 
    
    // 保存注册信息————赵凯强——（以增加微信）
	public function save()
	{
       // 实例化请求信息
		$request = $this->request;

		// 实例化学生并赋值
		//以前是新建对象，改为查找id是新增加的id的对象
			$Student = Student::where('id',$this->request->param('id'))->find();
			$Student->name = $request->param('name');
			$Student->num = $request->param('num');
			$Student->sex = $request->param('sex');
			$Student->password = $request->param('password');
			$Student->klass_id = $request->param('klass_id');

		$validate = new StudentValidate;

		if (!$validate->check($Student)) {
			return $this->error('数据不符合规范：' . $validate->getError());
		} else {
			// //以前是新建对象，改为查找id是新增加的id的对象
			// $Student = Student::where('id',$this->request->param('id'))->find();
			// $Student->name = $request->param('name');
			// $Student->num = $request->param('num');
			// $Student->sex = $request->param('sex');
			// $Student->password = $request->param('password');
			// $Student->klass_id = $request->param('klass_id');
			$Student->password = sha1($request->param('password'));
			// 添加数据
			if (!$Student->save()) {
				return $this->error('注册失败：', $Student->getError());
			} 
		}
		//存session，登陆
		Student::WxloginID($this->request->param('id'));
		//直接回主页
		return $this->success('注册成功', url('student/page'));
	}
    
    // 登录页面跳转————赵凯强
	public function index()
	{
		$name = "Test";
		$this->assign('name', $name);
		// 显示登录表单
		return $this->fetch();
	}
    
    //登录判定————赵凯强
	public function login()
	{
		$postData = $this->request->param();
		
		// 直接调用M层方法，进行登录
		if (Student::login($postData['num'], $postData['password'])) {
			return $this->success('登陆成功', url('Student/page'));
		} elseif (Teacher::login($postData['num'], $postData['password'])) {
			return $this->success('登陆成功', url('Teacher/page'));
		} elseif (Admin::login($postData['num'], $postData['password'])) {
			return $this->success('登陆成功', url('Admin/page'));
		} else {
			return $this->error('登陆失败，账号或密码错误', url('index'));
		}
	}
    
    // 学生注销————赵凯强
	public function SlogOut()
	{
		if(Student::logOut()) {
			return $this->success('退出成功', url('index'));
		} else {
			return $this->error('退出失败', url('student/page'));
		}
	}
    
    // 老师注销————赵凯强
	public function TlogOut()
	{
		if(Teacher::logOut()) {
			return $this->success('退出成功', url('index'));
		} else {
			return $this->error('退出失败', url('teacher/page'));
		}
	}
    
    // 管理员注销————赵凯强
	public function AlogOut()
	{
		if(Admin::logOut()) {
			return $this->success('退出成功', url('index'));
		} else {
			return $this->error('退出失败', url('admin/page'));
		}
	}

	public function Noterm()
	{
		return $this->fetch();
	}
}

