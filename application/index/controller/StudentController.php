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
use app\index\model\Classroom_time;
use app\index\model\Seattable;
use \app\index\validate\StudentValidate;
use think\facade\Request;

/**
 * $studentId = session('studentId');  //得到本学生Id
 * Request::url();  // 获取完整URL地址 不带域名
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-09 11:07:39
 */


class StudentController extends SIndexController
{	
//    public function index()
//    {
//        return $this->fetch();
//    }
    
	public function page()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取所有学期
        $terms = Term::all();

        //获取要查询的周次，若没有，就查询本周
        $week = $this->request->param('week');
        if (is_null($week)) {
            $week = Term::getWeek();
        }

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

        //把本学期所有周次编为一个数组
        $weeks = array();
        for ($i=0; $i < $Term->length; $i++) { 
            $weeks[$i] = $i+1;
        }
        $this->assign('weeks',$weeks);

        //取出本学期的所有课程，编为一个数组
        $courses = $Term->Course;
        //储存所有课程的ID
        $courseIds = [];
        foreach ($courses  as $value) {
          array_push($courseIds, $value->id);
        }
        // dump($courseIds);
        // return;

        // 获取本学生id
        $studentId = session('studentId');

        //查询本学期本本学生的所有课程
        $score = new Score();
        $getScore =  $score->where(['course_id'=>$courseIds, 'student_id'=>$studentId])->select();
        // $getScore =  $score->where('student_id',$studentId)->where('course_id',$courseIds)->select();
        //本学生本星期的课程表
        //前面是小节，后面是星期
        //0没用了！！！！！！！！！
        // dump($studentId);
        // dump($getScore);
        // return;
        $nullcourseinfo = new Courseinfo();
        $coursetable = array('1' => array('0','0','0','0','0','0','0','0') , 
            '2' => array('0','0','0','0','0','0','0','0') , 
            '3' => array('0','0','0','0','0','0','0','0') , 
            '4' => array('0','0','0','0','0','0','0','0') , 
            '5' => array('0','0','0','0','0','0','0','0') , 
            '6' => array('0','0','0','0','0','0','0','0') , 
            '7' => array('0','0','0','0','0','0','0','0') , 
            '8' => array('0','0','0','0','0','0','0','0') , 
            '9' => array('0','0','0','0','0','0','0','0') , 
            '10' => array('0','0','0','0','0','0','0','0') , 
            '11' => array('0','0','0','0','0','0','0','0')) ;

        // 得到本学期本学生所有课程成绩所对应的course_id
        $course = new Course();
        if(count($getScore) == 0) {
            // $coursetable = [];
        } else {
            //储存本学期本学生所有课程的course_id
            $scoreIds = [];
            foreach ($getScore as $score) {
                array_push($scoreIds, $score->course_id);
            }

            $courseinfos = Courseinfo::where(['course_id'=>$scoreIds, 'week'=>$week])->select();
            // dump($courseinfos);
            // return;
            foreach ($courseinfos as $key => $acourseinfo) {
                {
                    $coursetable[$acourseinfo->begin][$acourseinfo->weekday] = $acourseinfo;
                }
            }
            // dump($studentId);
            // dump($getScore);
            // // dump($coursetable);
            // dump($courseinfos);
            // return;
            // 通过成绩对应course_id得到本学期本学生所有课程
            // $courses = $course->where(['id'=>$scoreIds])->paginate(5);
        }

        //传入课程表
        $this->assign('coursetable',$coursetable);
        //传入要查询的星期
        $this->assign('week',$week);
        $this->assign('timetable',Term::$timetable);
		return $this->fetch();
	}

	public function online()
	{

        // 获取当前方法名
        $this->assign('isaction',Request::action());

		$this->assign('time',$time);    //发送各种时间变量
		return $this->fetch();
	}

	// 学生界面的课程查询--李美娜
	public function course()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取所有学期
		$terms = Term::all();
		$this->assign('terms', $terms);

		// 获取请求查询的学期课程，若没有就找最近的学期
		$id = $this->request->param('id/d');
		if (is_null($id)) {
			
			$i = count($terms);
            
            $id = $terms[$i-1]->id;
            foreach ($terms as $aaterm) {
                if ($aaterm->state === 1) {
                    $id = $aaterm->id;                  
                }
            }   		 
		}	

		$isTerm = Term::get($id);
         $this->assign('isTerm', $isTerm);
		
        // 取出本学期所有课程
        $termCourses = $isTerm->Course;

        
        $courseIds = [];

        foreach ($termCourses  as $value) {
          array_push($courseIds, $value->id);
        }

        // 获取本学生id
        $studentId = session('studentId');

        $score = new Score();
        $getScore = $score->where(['course_id'=>$courseIds, 'student_id'=>$studentId])->select();
        
        // dump($studentId);
        // dump($getScore);
        // return;
        // 得到本学期本学生所有课程成绩所对应的course_id
        $course = new Course();
        if(count($getScore) == 0) {
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
        // 获取当前方法名
        $this->assign('isaction','coursetime');
		return $this->fetch();
	}

	public function oncourse()
	{
		return $this->fetch();
	}

	// //学生模式——扫码进入课堂——刘宇轩
	// public function entercourse()
 //    {
        

 //        $id = $this->request->param('id/d');
        
        
 //        // 传入行列信息
 //        $courseinfo = Courseinfo::where('id', $id)->find();
 //        $this->assign('courseinfo',$courseinfo);
        
 //        // 传入所有学生
 //        $students = Oncourse::where('courseinfo_id',  $id)->order(['row', 'column'=>'asc'])->select();
 //        $this->assign('students', $students);
        
 //        // 人数比
 //        $nownumber = count($students);
 //        $this->assign('nownumber', $nownumber);
 //        $courseinfo = Courseinfo::get($id);
 //        $this->assign('number', $courseinfo->Course->number);

 //        // 传给v层一个变量，初始化为0
 //        $temp = 0;
 //        $this->assign('temp',$temp);
 //        // dump($nownumber);
 //        // return ;

 //        //根据传入参数，获取本节课的课程信息
 //        $courseinfo = Courseinfo::where('id',$this->request->param('id'))->find();
 //        //获取本节课的课程信息对应的课程
 //        $course = $courseinfo->Course;
 //        $student = Student::where('id',Session::get('studentId'))->find();
 //        $classroom = $courseinfo->Classroom;

 //        if(is_null(Score::where('course_id',$course->id)->where('student_id',$student->id)->find()))
 //        {
 //        	$score = new Score;
 //        	$score->student_id = $student->id;
 //        	$score->course_id = $course->id;
 //        	$score->usual_score = $score->exam_score = $score->total_score = $score->arrivals = $score->responds = 0; 
 //        	$score->save();
 //        }

 //        $oncourse = Oncourse::where('student_id',$student->id)->where('courseinfo_id',$courseinfo->id)->find();

 //        if (is_null($oncourse)) 
 //        {
 //        	$oncourse = new Oncourse;
 //        	$oncourse->student_id = $student->id;
 //        	$oncourse->courseinfo_id = $courseinfo->id;
 //        	$oncourse->column = $oncourse->row =  100;
 //            $oncourse->arrival = $oncourse->respond = 0;
 //        	$oncourse->save();
 //        } 

 //        $oncourse = Oncourse::where('student_id',$student->id)->where('courseinfo_id',$courseinfo->id)->find();
 //        // dump ($oncourse);

 //        $this->assign('oncourse',$oncourse);
 //        $this->assign('course',$course);
 //        $this->assign('courseinfo',$courseinfo);
 //        $this->assign('classroom',$classroom);

 //        $courseinfo = Courseinfo::where('weekday',Term::weekday())->where('week',Term::getWeek())->order('begin')->select();         //获取当天的所有课程
 //        // dump($classroom);
 //        return $this->fetch();
 //    }
    
    // 学生扫码选座位(新)————赵凯强
    // public function entercourse()
    // {
    //     $id = $this->request->param('id');
        
    //     $classroom_id = substr($id,0,4)*1;
    //     $row = substr($id,4,2)*1;
    //     $column = substr($id,6,2)*1;
    //     $time = Term::littleClass();
    //     if ($time<=0 || $time>=11) {
    //         return $this->error('上课时间已结束', url('/index/student/page'));
    //     }
    //     $student_id = session('studentId');
    //     // $classroom_times = Classroom::all();
    //     $classroom_time = Classroom_time::where('classroom_id',$classroom_id)->where('littleclass',$time)->find();

    //     // 如果有这个座位
    //     $seattable = Seattable::where('row',$row)->where('column',$column)->where('classroom_time_id',$classroom_time->id)->find();
    //     if ($seattable) {
    //         if ($seattable->student_id == $student_id) {
    //             return $this->error('您已成功扫码选择此座位，请不要重复扫码', url('/index/student/page'));
    //         } else {
    //             // 如果这个座位上有人
    //             if ($seattable->student_id ) {
    //                 // 给原学生发提示信息
                    

    //             }
    //             $seattable->student_id = $student_id;
                

    //             // 初始化本学生本教室其他座位信息
    //             $primarySeattable = Seattable::where('student_id',$student_id)->where('classroom_time_id',$classroom_time->id)->find();

    //             if ($primarySeattable) {
    //                 $primarySeattable->student_id = null;
    //                 if (!$primarySeattable->save()) {
    //                      return $this->error('信息保存异常，请重新扫码');
    //                 }
                    
    //             } else if($classroom_time->status){
    //                 // 若之前未选过其他座位，签到次数+1

    //                 $score = Score::where('student_id',$student_id)->where('course_id',$classroom_time->courseinfo->course_id)->find();
    //                 if ($score) {
    //                     // 如果本学生有本课程的一条数据，签到次数+1
    //                     $score->arrivals++;
    //                 } else {
    //                     // 如果没有，新建之
    //                     $score = new Score;
    //                     $score->student_id = $student_id;
    //                     $score->course_id = $classroom_time->courseinfo->course_id;
    //                     $score->usual_score = 0;
    //                     $score->exam_score = 0;
    //                     $score->total_score = 0;
    //                     $score->arrivals = 0;
    //                     $score->respond = 0;
    //                     $score->arrivals++;
    //                 }
    //                 if (!$score->save()) {
    //                      return $this->error('信息保存异常，请重新扫码');
    //                 }
    //             }

    //             if (!$seattable->save()) {
    //                 return $this->error('信息保存异常，请重新扫码');
    //             }
    //         }
    //     } else {
    //         // 初始化本学生本教室其他座位信息
    //         $primarySeattable = Seattable::where('student_id',$student_id)->where('classroom_time_id',$classroom_time->id)->find();
            
    //         if ($primarySeattable) {
    //             $primarySeattable->student_id = null;
    //             if (!$primarySeattable->save()) {
    //                 return $this->error('信息保存异常，请重新扫码');
    //             }
    //         } else if($classroom_time->status) {
                
                
    //             if (!$primarySeattable) {
    //                 // 签到次数+1
                    
    //                 $score = Score::where('student_id',$student_id)->where('course_id',$classroom_time->courseinfo->course_id)->find();

    //                 if ($score) {
    //                     // 如果本学生有本课程的一条数据，签到次数+1
    //                     $score->arrivals++;
    //                 } else {
    //                     // 如果没有，新建之
    //                     $score = new Score;
    //                     $score->student_id = $student_id;
    //                     $score->course_id = $classroom_time->courseinfo->course_id;
    //                     $score->usual_score = 0;
    //                     $score->exam_score = 0;
    //                     $score->total_score = 0;
    //                     $score->arrivals = 0;
    //                     $score->respond = 0;
    //                     $score->arrivals++;
    //                 }
    //                 if (!$score->save()) {
    //                      return $this->error('信息保存异常，请重新扫码');
    //                 }
    //             } else {
    //                 $primarySeattable->student_id = null;
    //                 $primarySeattable->signin = 0;
    //             }

                
    //         }
    //         $seattable = new Seattable;
            
    //         $seattable->classroom_time_id = $classroom_time->id;
    //         $seattable->row = $row;
    //         $seattable->column = $column;
    //         $seattable->student_id = $student_id;
    //         $seattable->role = 0;
    //         if (!$seattable->save()) {
    //             return $this->error('信息保存异常，请重新扫码');
    //         }
    //     }

    //     return $this->success('选座成功', url('/index/student/page'));
        
    // }

    // 学生扫码选座位(新中新)————赵凯强
    public function entercourse()
    {   
        $id = $this->request->param('id');
        
        $classroom_id = substr($id,0,4)*1;
        $row = substr($id,4,2)*1;
        $column = substr($id,6,2)*1;
        $time = Term::littleClass();
        if ($time<=0 || $time>11) {
            return $this->error('上课时间已结束', url('/index/student/page'));
        }
        $student_id = session('studentId');
        $classroom_time = Classroom_time::where('classroom_id',$classroom_id)->where('littleclass',$time)->find();

        
        $seattable = Seattable::where('student_id',$student_id)->where('classroom_time_id',$classroom_time->id)->find();

        // 如果这个学生原来签过到
        if($seattable) {
            $primaryStudent = Seattable::where('row',$row)->where('column',$column)->where('classroom_time_id',$classroom_time->id)->find();

            // 如果这个座位原来有学生
            if ($primaryStudent) {
                // 如果这个学生是他自己
                if ($primaryStudent->student_id == $student_id) {
                    return $this->error('您已成功扫码选择此座位，请不要重复扫码', url('/index/student/page'));    
                }

                // 通知他


                // 他行列信息清空
                $primaryStudent->row = null;
                $primaryStudent->column = null;
                if ($primaryStudent->save()) {
                    return $this->error('信息保存异常，请重新扫码');
                }
            }
            
            // 将新的行列数保存到学生那条数据里
            $seattable->row = $row;
            $seattable->column = $column;
            if ($seattable->save()) {
                    return $this->error('信息保存异常，请重新扫码');
            }

        } else {  // 如果这个学生原来没选过座位
             
            // 创建一条新数据
            $seattable = new Seattable;
            
            $seattable->classroom_time_id = $classroom_time->id;
            $seattable->row = $row;
            $seattable->column = $column;
            $seattable->student_id = $student_id;
            $seattable->role = 0;
            if (!$seattable->save()) {
                return $this->error('信息保存异常，请重新扫码');
            } 

            
            $score = Score::where('student_id',$student_id)->where('course_id',$classroom_time->courseinfo->course_id)->find();

            if ($score) {
                // 如果本学生有本课程的一条数据，签到次数+1
                $score->arrivals++;
            } else {
                // 如果没有，新建之
                $score = new Score;
                $score->student_id = $student_id;
                $score->course_id = $classroom_time->courseinfo->course_id;
                $score->usual_score = 0;
                $score->exam_score = 0;
                $score->total_score = 0;
                $score->arrivals = 0;
                $score->respond = 0;
                $score->arrivals++;
            }
            if (!$score->save()) {
                 return $this->error('信息保存异常，请重新扫码');
            }
        }

        return $this->success('选座成功', url('/index/student/page'));
    }

	//学生模块——座位信息储存
	public function seatsave()
	{
		$seat = $this->request->param();

       
		$oncourse = Oncourse::where('id',$seat["oncourse_id"])->find();

        // dump($oncourse);
        // return ;

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
        $oncourseinfo = Oncourse::where('courseinfo_id',$seat["courseinfo_id"])->where('row',$seat["row"])->where('column',$seat["column"])->find();
        if ($oncourseinfo != Null) {
            return $this->error('该座位已有人使用，请重新输入');
        }

        $oncourse->column = $seat["column"];
        $oncourse->row = $seat["row"];

        $original = $oncourse->arrival;
        
        $oncourse->arrival = 1;
        // dump($original);
        // dump($oncourse->arrival);
        // return ;
        if ($original == 0 && $oncourse->arrival == 1 ) {
            $score = Score::where('student_id', $oncourse->student_id)->where('course_id',$oncourse->courseinfo->course_id)->find();
            $score->arrivals++;
            if (!$score->save()) {
            // return $this->success('恭喜您已完成签到',url('entercourse?id='.$seat['courseinfo_id']));
        
                return $this->success('签到失败',url('entercourse?id='.$seat['courseinfo_id']));
            }
        }
        
        if ($oncourse->save()) {
        	// return $this->success('恭喜您已完成签到',url('entercourse?id='.$seat['courseinfo_id']));
        	return $this->success('恭喜您已完成签到',url('page'));
        }

		// dump($seat);
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

		// 获取所有学期
        $terms = Term::all();
        $this->assign('terms', $terms);

        // 获取请求查询的学期课程，若没有就找最近的学期
        $id = $this->request->param('id/d');
        if (is_null($id)) {
            
            $i = count($terms);
            
                $id = $terms[$i-1]->id;
                foreach ($terms as $aaterm) {
                    if ($aaterm->state === 1) {
                        $id = $aaterm->id;                  
                    }
                }
                
                 
        }    
        $isTerm = Term::get($id);
        $this->assign('Termname', $isTerm->name);   

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

         if($Student->password != sha1($this->request->param('oldpassword'))){
            return $this->error('原密码不正确');
         }

         $Student->password = $this->request->param('newpassword');

        $validate = new \app\index\validate\StudentValidate();
        // $validate = Validate::make(StudentValidate::getUpdateValidate());

        if (!$validate->check($Student)) {
            return $this->error('密码不符合规范：' . $validate->getError());
        } else {
            $Student->password = sha1($Student->password);
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
        // $time = new Time();
        // dump($time->time);
        return $this->fetch();
    }

    public function OpenIdtest()
    {
    	$test = "我是Student控制器的一个方法";
    	dump ($test);
    }
}
