<?php
namespace app\index\controller;
use app\index\model\Course;
use app\index\model\Area;
use app\index\model\College;
use app\index\model\Term;
use app\index\model\Time;
use app\index\model\Academy;
use app\index\model\Klass;
use app\index\model\Teacher;
use app\index\model\KlassCourse;
use app\common\model\Index;
use app\index\model\Oncourse;
use app\index\model\Courseinfo;
use app\index\model\Score;
use think\Controller;
use app\index\model\Classroom;
use think\facade\Session;
use app\index\model\Student;
use think\Db;
use think\facade\Request;

/**
 * $teacherId = session('teacherId'); 
 * Request::action(); // 获取当前方法名
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-27 20:41:18
 */
class TeacherController extends TIndexController
{

	public function page()
	{
        // 获取当前方法名
        $this->assign('isaction',Request::action());
        
        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);
        $time = Term::timeAll();
        $this->assign('time', $time);
        return $this->fetch();
	}	
    

    // 课程——赵凯强
    public function course()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 得到本教师id
        $Id = session('teacherId');
        
        $Course = new Course();

        // 获取查询信息
        $name = $this->request->get('name');

        $courses = $Course->where(['teacher_id' => $Id])->where('name', 'like', '%' . $name . '%')->paginate(5, false, [
            'query' =>[
                'name' => $name,
            ],
        ]);

        $this->assign('courses', $courses);
       
    	return $this->fetch();
    }
    
    // 课程增加跳转——赵凯强
    public function courseadd()
    {
        $terms = Term::all();
        $this->assign('terms', $terms);
        
        $teacherId = session('teacherId');
        $teacher = Teacher::get($teacherId);
        $this->assign('teacher', $teacher);
        
        $colleges = College::all();
        $this->assign('colleges', $colleges);

        return $this->fetch();
    }

    // 课程增加保存——赵凯强
    public function coursesave()
    {
        // dump($this->request->param());
        // return ;
        // 存课程信息
        $Course = new Course();
        $Course->name = $this->request->param('name');
        $Course->num = $this->request->param('num');
        $Course->term_id = $this->request->param('term_id/d');
        $Course->teacher_id = $this->request->param('teacher_id/d');
        $Course->type = $this->request->param('type/d');

        // 验证
        if (!$Course->save()) {
            return $this->error('保存错误1：' . $Course->getError());
        }

        //--------------------增加班级课程信息------------

        // 接收klass_id这个数组
        $klassIds = $this->request->param('klass_id/a');

        // 利用klass_id这个数组，拼接包括klass_id和course_id的二维数组。
        if (!is_null($klassIds)) {
            if (!$Course->Klasses()->saveAll($klassIds)) {
                return $this->error('课程-班级保存错误：' . $KlassCourse->getError());
            } 
        }

        
        // ------------------------新增班级课程信息(end)-----
        unset($Course); 

        return $this->success('操作成功', url('course'));
    }
    
    // 课程编辑跳转——赵凯强
    public function courseedit()
    {
        $id = $this->request->param('id/d');
        $Course = Course::get($id);

        if (is_null($Course)) {
            return $this->error('不存在ID为' . $id . '的记录');
        }

        $this->assign('Course', $Course);

        // 学期
        $terms = Term::all();
        $this->assign('terms', $terms);

        // 老师
        $teacherId = session('teacherId');
        $teacher = Teacher::get($teacherId);
        $this->assign('teacher', $teacher);
        
        // 学院
        $colleges = College::all();
        $this->assign('colleges', $colleges);

        return $this->fetch();
    }
    
    // 课程编辑保存——赵凯强
    public function courseupdate()
    {
        // 获取当前课程
        $id = $this->request->post('id/d');
        if (is_null($Course = Course::get($id))) {
            return $this->error('不存在ID为' . '的记录');
        }

        // 更新课程名
        $Course->name = $this->request->param('name');
        $Course->num = $this->request->param('num');
        $Course->term_id = $this->request->param('term_id/d');
        $Course->teacher_id = $this->request->param('teacher_id/d');
        $Course->type = $this->request->param('type/d');
        
        if (!$Course->save()) {
            return $this->error('课程信息更新发生错误：' . $Course->getError());
        }

        // 删除原有信息
        $map = ['course_id'=>$id];

        // 执行删除操作。 由于可能存在成功删除0条记录， 故使用false来进行判断，而不能使用
        // if (!KlassCOurse::where($map)->delete())
        //我们认为， 删除0条记录， 也是成功
        if (false === $Course->KlassCourses()->where($map)->delete()) {
            return $this->error('删除班级课程关联信息发生错误' . $Course->KlassCourses()->getError());
        }

        //增加新增数据，执行添加操作。
        $klassIds = $this->request->param('klass_id/a');
        if (!is_null($klassIds)) {
            if (!$Course->Klasses()->saveAll($klassIds)) {
                return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
            }
        }

        return $this->success('更新成功', url('course'));
    }

    // 课程删除——赵凯强
    public function coursedelete()
    {
        // 获取get数据
        $id = $this->request->param('id/d');

        if (is_null($id) || 0 === $id) {
            return $this->error('未获取到ID信息');
        }

        // 获取要删除的对象
        $Course = Course::get($id);

        // 要删除的对象不存在
        if (is_null($Course)) {
            return $this->error('不存在id为' . $id . '的课程');
        }

        // 删除对象
        if (!$Course->delete()) {
            return $this->error('删除失败：' . $Course->getError());
        }

        // 进行跳转
        return $this->success('删除成功', url('course'));
  
    }
    
    // 教师模块课程管理查看上课时间——刘宇轩
    public function coursesee()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());
        //获取传入的课程ID
        $id = $this->request->param('id/d');
        //从数据库取出此课程
        $course = Course::where('id',$id)->find();
        $this->assign('course',$course);
        //取出此课程的课程时间
        $Tables = Courseinfo::getCourseTable($id);
        $this->assign('table',$Tables);
        //传入教室对象
        $classroom = new classroom;
        $this->assign('classroom',$classroom);
        // dump($Tables);
        // return ;
        return $this->fetch();
    }

    // 教师模块课程管理查看修改上课时间——刘宇轩
    public function courseweek()
    {
        $id = $this->request->param('id/d');
        $weekday = $this->request->param('weekday/d');
        $weekdayorigin = $weekday;
        $weekday = Term::getWeekday($weekday - 1);
        $begin = $this->request->param('begin/d');
        $weeks = array();
        $classroom_id = Courseinfo::getCourseClassroom($id,$weekdayorigin,$begin);

        $classrooms = Classroom::select();
        $termLength = Term::TermLength();
        for ($i=0; $i < $termLength; $i++) { 
            $weeks[$i] = $i;
        }
        $course = Course::get($id);
        $courseinfo = new Courseinfo;
        $this->assign('classrooms',$classrooms);
        $this->assign('classroom_id',$classroom_id);
        $this->assign('courseinfo',$courseinfo);
        $this->assign('weekdayorigin',$weekdayorigin);
        $this->assign('weekday',$weekday);
        $this->assign('begin',$begin);
        $this->assign('weeks',$weeks);
        $this->assign('course',$course);

        $areas = Area::all();
        $this->assign('areas', $areas);
        return $this->fetch();
    }

    //教师模块课程管理更新上课时间——刘宇轩
    public function courseweeksave()
    {
        $id = $this->request->post('id');
        $weekday = $this->request->post('weekday');
        $begin = $this->request->post('begin');
        $weeks = $this->request->post('weeks');
        $length = $this->request->post('length');
        $classroom_id = $this->request->post('classroom_id');

        
        // dump($id);   
        // return ; 
        
        
        if (is_null($weeks)) {
            return $this->error('周次不能为空');
        }
        if (is_null($classroom_id)) {
            return $this->error('教室不能为空');
        }
        
        if (false === Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begin)->delete()) {
            return $this->error('更新课程信息失败，删除错误');
        }
        foreach ($weeks as $key => $week) {
            $courseinfo = new Courseinfo();
            $courseinfo->course_id = $id;
            $courseinfo->weekday = $weekday;
            $courseinfo->begin = $begin;
            $courseinfo->length = $length;
            $courseinfo->classroom_id = $classroom_id;
            $courseinfo->week = $week;
            if (!$courseinfo->save()) {
                return $this->error('课程信息保存错误：');
            }
        }
        return $this->success('更新成功',url('coursesee?id=' . $id));
    }

    //教师模块课程管理删除上课时间——刘宇轩
    public function courseweekdelete()
    {
        $id = $this->request->param('id');
        
        $weekday = $this->request->param('weekday');
        $begin = $this->request->param('begin');
        if (false === Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begin)->delete()) {
            return $this->error('删除错误');
        }
        return $this->success('删除成功',url('coursesee?id=' . $id));
    }

    public function coursetime() // 废弃
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

    	return $this->fetch();
    }

    // 教师界面教室管理--李美娜
    public function classroom()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

        // 实例化classroom
        $Classroom = new Classroom();
        
        // 获取查询信息
        $name = $this->request->get('name');

        $classrooms = $Classroom->where('classroomname', 'like', '%' . $name . '%')->paginate(5, false, [
            'query' =>[
                'name' => $name,
            ],
        ]);
        
        // 向V层传数据
        $this->assign('classrooms',$classrooms);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }


    // 教师模块教室管理插入教室--李美娜
    public function classroomadd()
    {
        return $this->fetch();
    }
    
    // 教师模块教室管理插入教室--李美娜
    public function classroomsave()
    {
        // 接收传入数据
        $postData = $this->request->post();

        // 实例化Student空对象
        $Classroom = new Classroom();

        // 为对象赋值
        $Classroom->classroomplace = $postData['classroomplace'];
        $Classroom->classroomname = $postData['classroomname'];
        $Classroom->row = $postData['row'];
        $Classroom->column = $postData['column'];

        // 添加数据
        if (!$Classroom->save())
        {
            return $this->error('数据添加错误：' . $Classroom->getError());
        }
        return $this->success('操作成功', url('classroom'));
    }


    // 教师模块教室管理删除教室--李美娜
    public function classroomdelete()
    {
        // 获取pathinfo传入的ID值
        $id = $this->request->param('id/d');

        if (is_null($id) || 0 === $id){
            return $this->error('未获取ID信息');
        }

        // 获取要删除的对象
        $Classroom = Classroom::get($id);

        // 要删除的对象存在
        if (is_null($Classroom)) {
            return $this->error('不存在id为' . $id . '的教室，删除失败');
        }

        // 删除对象
        if (!$Classroom->delete()) {
            return $this->error('删除失败:');
        }

        // 进行跳转
        return $this->success('删除成功',url('classroom'));
    }

    // 教师界面教室管理编辑教室--李美娜
    public function classroomedit()
    {
        // 获取pathinfo传入的ID值
        $id = $this->request->param('id/d');

        // 在Student表模型中获取当前记录
        if (is_null($Classroom = Classroom::get($id))) {
            return $this->error('系统未找到ID为' . $id . '的记录',url('classroom'));
        }

        // 向V层传数据
        $this->assign('classroom', $Classroom);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }


    // 教师界面教室管理更新教室--李美娜
    public function classroomupdate()
    {
        try {
            // 接收数据，获取要更新的关键字信息
            $id = $this->request->post('id/d');
            $message = '更新成功';
            $Classroom = Classroom::get($id);

            if (!is_null($Classroom)) {
                // 写入要更新的数据
                $Classroom->classroomplace = $this->request->post('classroomplace');
                $Classroom->classroomname = $this->request->post('classroomname');
                $Classroom->row = $this->request->post('row');
                $Classroom->column = $this->request->post('column');

                // 更新
                if (false === $Classroom->save())
                {
                    $message =  '更新失败' . $Classroom->getError();
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);  
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        // 进行跳转
        return $this->success($message,url('classroom'));
    }

    //教师模块上课模式——刘宇轩
    public function online() 
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());


        $teacher = Teacher::where('id',Session::get('teacherId'))->find(); //获取教师信息
        
        $allcourseinfo = Courseinfo::where('weekday',Term::weekday())->where('week',Term::getWeek())->order('begin')->select();         //获取当天的所有课程
        
        $courseinfo = [];     //依次判断每节课是否为本教师的课程
        foreach ($allcourseinfo as $key => $acourse) {
            if ($acourse->course->teacher_id == Session::get('teacherId')) {
                $courseinfo[$key] = $acourse;
            }
        }
        // echo Term::weekday();
        // echo Term::getWeek();
        $this->assign('courseinfo',$courseinfo);
        // dump($allcourseinfo);
        // return;
    	return $this->fetch();
    }

    //教师模块课程首页——刘宇轩
    public function onlinehome()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        $courseinfo = Courseinfo::where('id',$this->request->param('id/d'))->find();

        // dump($courseinfo);

        $this->assign('courseinfo',$courseinfo);
        return $this->fetch();
    }

    //教师模块签到界面——刘宇轩 
    public function setcourse()
    {
        $courseinfo = Courseinfo::where('id',$this->request->param('id/d'))->find();
        // $classroom[0] = $courseinfo->classroom->classroomplace . $courseinfo->classroom->classroomname;
        // $classroom[1] = $courseinfo->classroom->row;
        // $classroom[2] = $courseinfo->classroom->column;
        // $url = Term::$domainname . url('student/entercourse?id=' . $courseinfo->getData('id'));
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/Wxindex/online?id='.$courseinfo->getData('id') ;
        // $this->assign('classroom',$classroom);
        $this->assign('courseinfo',$courseinfo);
        $this->assign('url',$url);
        return $this->fetch();
    }

    //教师模块随机提问界面——刘宇轩
    public function onlinesignin()
    {
        //取出本节课的课程信息
        $courseinfo = Courseinfo::where('id',$this->request->param('id/d'))->find();
        //从中间表取出所有学生信息
        $students = Score::where('course_id',$courseinfo->course->id)->select();
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
        $this->assign('courseinfo',$courseinfo);
        $this->assign('student',$thestudent);

        return $this->fetch();
    }

    public function entercourse()
    {
        echo $this->request->param('id');
    }

    //教师模块上课模式——刘宇轩
    public function incourse ()
    {
        $teacher = Teacher::where('id',Session::get('teacherId'))->find();
        $littleClass = Term::littleClass();

        echo $littleClass;
        return $this->fetch();
    }

    //教师模块成绩录入界面——刘宇轩
    public function grade()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        // 得到本教师id
        $id = session('teacherId');

        //取出本教师的全部课程
        $courses = Course::where('teacher_id',$id)->paginate(5);

        $klass = new Klass;

        $klasscourses = KlassCourse::select();//取出班级课程中间表的全部信息
        
        //对于每个课程，取出对应的多个班级信息
        foreach ($courses as $acourse) {
            $klasses = KlassCourse::where('course_id',$acourse->id)->select();
            $str = "";
            //对于每个班级，用ID取出班级名称，并连接字符串
            foreach ($klasses as $aclass) {
                $str1 = klass::get($aclass->klass_id)->name;
                $str = $str.",".$str1; 
            }
            //把字符串信息合并到课程信息中
            $acourse->klass = substr($str,1,-1);
        }
        //发送课程信息
        $this->assign('courses',$courses);

        return $this->fetch();
    }

    //教师模块成绩录入二级界面课程详情——刘宇轩
    public function gradeinfo()
    {
        // 获取当前方法名
        $this->assign('isaction','grade');
        //获取课程id
        $id = $this->request->param('id/d');

        $courses = new course();
        //通过课程id获取当前课程
        $course = Course::get($id);
        //根据课程取出所有课程信息（每节课）
        $infos = $courses->Courseinfo()->where('course_id',$id)->order('week')->order('weekday')->paginate(10);

        $this->assign('course',$course);
        $this->assign('infos',$infos);

        return $this->fetch();
    }

    //教师模块成绩录入三级界面出勤详情——刘宇轩
    public function gradeoncourse()
    {
        // 获取当前方法名
        $this->assign('isaction','grade');
    
        $id = $this->request->param('id/d');

        $courseinfo = Courseinfo::get($id);
        $course = $courseinfo->course()->find();
        $oncourse = new Oncourse;
        $arrival1 = $oncourse->where('courseinfo_id',$id)->where('arrival',1)->select();
        $arrival0 = $oncourse->where('courseinfo_id',$id)->where('arrival',0)->select();
        $this->assign('arrival1',$arrival1);
        $this->assign('arrival0',$arrival0);
        $this->assign('courseinfo',$courseinfo);
        $this->assign('course',$course);
        return $this->fetch();
    }

    //教师模块成绩录入-录入界面——刘宇轩
    public function gradeadd()
    {
        // 获取当前方法名
        $this->assign('isaction','grade');
        //根据传入课程id获取课程
        $id = $this->request->param('id/d');
        $course = Course::get($id);
        //获取此课程所有学生的签到和成绩信息
        $scores = Score::where('course_id',$id)->select();

        $this->assign('course',$course);
        $this->assign('scores',$scores);
        return $this->fetch();
        // dump($course);
        // dump($scores);
        // return;
    }

    //教师模块成绩更新-刘宇轩
    public function gradeupdate()
    {
        $scores = $this->request->post();
        $key = $this->request->post('key');
        // dump($this->request->post());
        //dump ($key);
        //dump ($scores);
        //dump ($scores["id"]["0"]);
        // return;

        $message = '更新成功';
        for ($i=0; $i <= $key; $i++) { 
            $score = score::where('id',$scores["id"][$i])->find();

            if (!is_null($score)){
                $score->score1 = $scores["score1"][$i];
                $score->score2 = $scores["score2"][$i];
                $score->scoresum = (int)$scores["scoresum"][$i];
                                if (false === $score->save())
                {
                    $message = '更新失败';
                    return $this->error($message,url('grade'));
                }
            }else{
                $message = '所更新的记录不存在';
                return $this->error($message,url('grade'));
            }

            
        }
        return $this->success($message,url('grade'));
    }

}

