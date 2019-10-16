<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Student;
use app\index\model\Klass;
use app\index\model\Teacher;
use app\index\model\Admin;
use app\index\model\College;
use app\index\model\Term;
use app\index\model\Classroom;
use app\index\model\Classroom_time;
use app\index\model\Seattable;
use app\index\validate\StudentValidate;

/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-16 20:43:10
 */
class LoginController extends Controller
{
	//选择个人角色，是学生还是教师
	public function judgeRole($id)
	{
 		$this->assign('id',$id);
 		return $this->fetch();
	}
	//跳转到教师登录界面，并验证是否为教师
    public function verificationTeacher($id)
    {
      $this->assign('id',$id);
      return $this->fetch(); 
    }
    //对前台传过来的信息进行处理(通过则登录成功，删除学生表里的数据，将openId存进去，不通过则回到选择界面)
    public function machining()
    {
    $request = $this->request;
    $id = $request->param('id');
    $num = $request->param('num');
    $password = $request->param('password');
    $people = Teacher::where('num',$num)->where('password',$password)->find();
    $student = Student::get($id);
    if(!is_null($people) && !is_null($student))
    {
    	$people->openid = $student->openid;
    	if( $people->save() && $student->delete())
         {
    	   Teacher::login($num,$password);
    	   return $this->success('认证成功', url('Teacher/page'));
         }
    }
    else
    {
        return $this->error('认证失败，请重新选择', url('Login/judgeRole?id='. $id.'&openid='.$student->openid));
    }
    }
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
			//$Student->password = sha1($request->param('password'));
			// 添加数据
			$Student->password = sha1($Student->password);
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

	public function OnlineSee()
	{
	    // 获取当前方法名
        //$this->assign('isaction',Request::action());

        //获取教室ID
        $id = $this->request->param('id');
        //根据ID获取教室
        $classroom = Classroom::where('id', $id)->find();
        $this->assign('classroom',$classroom);

        //获取当前小节，根据小节找到classroom_time
        $littleClass = Term::littleClass();
        $Classroom_time = Classroom_time::where('classroom_id',$classroom->id)->where('littleClass',$littleClass)->find();
        $this->assign('Classroom_time', $Classroom_time);

        //找到当前教室当前小节的所有学生
        $students = Seattable::where('Classroom_time_id',  $Classroom_time->id)->order(['row', 'column'=>'asc'])->select();
        $this->assign('students', $students);
        // dump($students);
        // return ;

        // 人数比
        $nownumber = count($students);
        $this->assign('nownumber', $nownumber);
        // $courseinfo = Courseinfo::get($id);
        // $this->assign('number', $courseinfo->Course->number);
        
        // 传给v层一个变量，初始化为0
        $temp = 0;
        $this->assign('temp',$temp);
        return $this->fetch();
	}

	//教师模块随机提问界面_与教室关联——刘宇轩
    public function onlinesignin1()
    {
        //取出本节课的课程信息
        $Classroom_time = Classroom_time::where('id',$this->request->param('id/d'))->find();
        
        //从中间表取出所有学生信息
        $students = Seattable::where('Classroom_time_id', $Classroom_time->id)->select();
        // dump($students);
        // return ;
        if (count($students) == 0) {
            
            return $this->error('当前没有学生签到');
        }
        
        // 建一个空数组储存学生信息
        $ids = [];
        //对于每个学生取出ID
        foreach ($students as $key => $astudent) {
            $ids[$key] = $astudent->student_id;
        }

        //打乱顺序
        shuffle($ids);
        //如果传入数据为空，并且传入id等于随机数id，则再次随机
        if ($this->request->param('student') != null && count($ids) != 1 && count($ids) != 0) {
            if ($ids[0] == $this->request->param('student')){
                $id = $ids[1];
            }else{
                $id = $ids[0];
            }}
        else{
            $id = $ids[0];
        }
        //取第一个值，揪出这个倒霉孩子
        $thestudent = Student::where('id',$id)->find();
        // dump($id);
        //向V层传值
        $this->assign('student',$thestudent);

        return $this->fetch();
    }

	//使用js调用该方法，控制弹窗
	public function judgeFocus()
	{
	 return 1;	
	}
	//使用js调用该方法，进行绑定
	public function binding()
	{
	 return 2;	
	}
}