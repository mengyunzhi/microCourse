<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use think\Validate;
use app\index\model\Student;
use app\index\model\Term;
use app\index\model\Klass;
use app\index\model\KlassCourse;
use app\index\model\Course;
use app\index\model\Teacher;
use app\index\model\Score;
use app\index\model\Courseinfo;
use app\index\model\Oncourse;
use app\index\model\Common;
use think\facade\Session;
use app\index\model\Classroom;
use \app\index\validate\StudentValidate;
use think\facade\Request;

/**
 * $studentId = session('studentId');  //得到本学生Id
 * Request::url();  // 获取完整URL地址 不带域名
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-19 20:21:52
 */


class StudentController extends SIndexController
{	
	public function page()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		return $this->fetch();
	}

	public function online()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		$time[0] = Term::TermLength();  //获取学期
        $time[1] = date('Y-m-d H:i:s'); //获取日期
        $time[2] = Term::getWeek();     //获取教学周次
        $time[3] = Term::getWeekday(Term::weekday());   //获取星期
        $time[4] = Term::largeClass();  //获取大节

		$this->assign('time',$time);    //发送各种时间变量
		return $this->fetch();
	}

	// 学生界面的课程查询--李美娜
	public function course()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		// 查找已激活的学期，主要用于状态栏的显示
		$term = Term::where('state',1)->find();	
		if ($term === null) {
			$termid = 0;
		} else {
			$termid = $term->id;
		}	

		$this->assign('termid', $termid);

		// 获取所有学期
		$terms = Term::all();
		$this->assign('terms', $terms);

		// 获取请求查询的学期课程，若没有就找最近的学期
		$id = $this->request->param('id/d');
		if (is_null($id)) {
			$i = 0;
			foreach ($terms as $aterm) {
			 	$i++;
			 }
			 $id = $terms[$i-1]->id;
			 foreach ($terms as $aaterm) {
			 	if ($aaterm->state === 1) {
			 		$id = $aaterm->id;			 		
			 	}
			 }
		}		
		$Term = Term::get($id);
		$this->assign('Term', $Term);

        $courses = $Term->Course;
        $courseIds = [];
        foreach ($courses  as $value) {
          array_push($courseIds, $value->id);
        }

        // 获取本学生id
        $studentId = session('studentId');

        $score = new Score();
        $getScore =  $score->where(['course_id'=>$courseIds, 'student_id'=>$studentId])->select();
        
        // 得到本学期本学生所有课程成绩所对应的course_id
        $course = new Course();
        if(is_null($getScore)) {
            $courses = [];
        } else {
            $scoreIds = [];
            foreach ($getScore as $score) {
                array_push($scoreIds, $score->course_id);
            }
            
            // 通过成绩对应course_id得到本学期本学生所有课程
            $courses = $course->where(['id'=>$scoreIds])->paginate(5);
            
        }
         // 获取请求学期的课程
        $this->assign('courses', $courses);
		return $this->fetch();	
	}

	public function coursetime()
	{
		return $this->fetch();
	}

	public function oncourse()
	{
		return $this->fetch();
	}

	//学生模式——扫码进入课堂——刘宇轩
	public function entercourse()
    {
        $courseinfo = Courseinfo::where('id',$this->request->param('id'))->find();
        $course = $courseinfo->Course;
        $id = $course->id;
        $student = Student::where('id',Session::get('studentId'))->find();
        $classroom = $courseinfo->Classroom;

        if(is_null(Score::where('course_id',$id)->where('student_id',$student->id)->find()))
        {
        	$score = new Score;
        	$score->student_id = $student->id;
        	$score->course_id = $id;
        	$score->score1 = $score->score2 = $score->scoresum = $score->arrivals = $score->responds = 0; 
        	$score->save();
        }

        $oncourse = Oncourse::where('student_id',$student->id)->where('courseinfo_id',$courseinfo->id)->find();

        if (is_null($oncourse)) 
        {
        	$oncourse = new Oncourse;
        	$oncourse->student_id = $student->id;
        	$oncourse->courseinfo_id = $courseinfo->id;
        	$oncourse->column = $oncourse->row = $oncourse->arrival = $oncourse->respond = 0;
        	$oncourse->save();
        } 

        $oncourse = Oncourse::where('student_id',$student->id)->where('courseinfo_id',$courseinfo->id)->find();
        dump ($oncourse);

        $this->assign('oncourse',$oncourse);
        $this->assign('course',$course);
        $this->assign('courseinfo',$courseinfo);
        $this->assign('classroom',$classroom);

        $time[0] = Term::TermLength();  //获取学期
        $time[1] = date('Y-m-d H:i:s'); //获取日期
        $time[2] = Term::getWeek();     //获取教学周次
        $time[3] = Term::getWeekday(Term::weekday());   //获取星期
        $time[4] = Term::largeClass();  //获取大节
        $courseinfo = Courseinfo::where('weekday',Term::weekday())->where('week',Term::getWeek())->order('begin')->select();         //获取当天的所有课程
        $this->assign('time',$time);    //发送各种时间变量
        dump($classroom);
        return $this->fetch();
    }

	//学生模块——座位信息储存
	public function seatsave()
	{
		$seat = $this->request->post();
		$oncourse = Oncourse::where('id',$seat["oncourse_id"])->find();
		if (is_null($oncourse)) 
        {
        	return $this->error('课程信息异常，请重新扫码进入');
        } 
        if ($seat["column"] == "") 
        {
        	return $this->error('行号为空，请重新输入');
        } 
        if ($seat["row"] == "") 
        {
        	return $this->error('列号为空，请重新输入');
        } 
        if (($seat["column"])>($seat["classroom_column"])) 
        {
        	return $this->error('列号输入超出范围，请重新输入');
        } 
        if (($seat["row"])>($seat["classroom_row"])) 
        {
        	return $this->error('行号输入超出范围，请重新输入');
        } 
        $oncourse->column = $seat["column"];
        $oncourse->row = $seat["row"];
        $oncourse->arrival = 1;
        if ($oncourse->save()) {
        	// return $this->success('恭喜您已完成签到',url('entercourse?id='.$seat['courseinfo_id']));
        	return $this->success('恭喜您已完成签到',url('online?success=1'));
        }

		dump($seat);
		// return $this->fetch();
		return $this->error('提交失败，请重新扫码进入');
	}
    
    // 学生成绩查询——赵凯强
	public function score()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        
        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		$terms = Term::all();
        $this->assign('terms', $terms);
        $id = $this->request->param('id/d');
        
        if (is_null($id)) {
        	$i = 0;
	        foreach($terms as $term){
	        	$i++;
	        }
	        $id = $terms[$i-1]->id;
	        foreach ($terms as $term){
	        	if ($term->state === 1){
	                $id = $term->id;
	        	}
	        }
        }
        // 获取本学期id
        $isTerm = Term::get($id);
        $this->assign('isTerm', $isTerm);
        
        // 得到本学期所有课程id
        $courses = $isTerm->Course;
       
        $courseIds = [];
        foreach ($courses  as $value) {
            array_push($courseIds, $value->id);
        }

        // 获取本学生id
        $studentId = session('studentId');

        // 得到本学期本学生所有课程成绩
        $score = new Score();
        $getScore =  $score->where(['course_id'=>$courseIds, 'student_id'=>$studentId])->paginate(5);

        
        $this->assign('scores', $getScore);
        return $this->fetch();   
	}

    // 学生信息页面————赵凯强
	public function info()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);
        
        // 获取本学生id
		$id = session('studentId');

		$student = Student::get($id);
		$this->assign('student', $student);
		return $this->fetch();
	}	
    
    // 学生信息编辑页面跳转————赵凯强
    public function infoedit()
    {

      $id = $this->request->param('id/d');

      // 判断是否存在当前记录
      if (is_null($Student = Student::get($id))) {
      	return $this->error('未找到ID为' . $id  . '的记录');
      }

      $this->assign('Student', $Student);
      $klasses = Klass::all();
      $this->assign('klasses', $klasses);
      return $this->fetch();
    }
    // 学生信息编辑保存————赵凯强
    public function infoUpdate()
    {
    	$id = $this->request->param('id/d');

    	// 获取传入的学生信息
    	$Student = Student::get($id);
    	if (is_null($Student)) {
    		return $this->error('系统未找到ID为' . $id . '的记录');
    	}

        $Student->password = $this->request->param('password');

        $validate = new \app\index\validate\StudentValidate();
        // $validate = Validate::make(StudentValidate::getUpdateValidate());

        if (!$validate->check($Student)) {
            return $this->error('修改数据不符合规范：' . $validate->getError());
        } else {
            if (!$Student->save()) {
                return $this->error('更新错误：' . $Student->getError());
            } else {
                return $this->success('操作成功', url('info'));
            }
        }
    	
    }	

    public function password()
    {
      	return $this->fetch();
    }

    public function aboutourteam()
    {
    	// $day = Term::largeClass();
    	// return $day;
    	// $day1 = "2019-08-10";
		// $day2 = date("Y-m-d");
		// echo $day2;
		// echo "空格";
		// $diff = index::weekday($day2);
		// echo $diff;
      	// return $this->fetch();
    }

    public function OpenIdtest()
    {
    	$test = "我是Student控制器的一个方法";
    	dump ($test);
    }
}
