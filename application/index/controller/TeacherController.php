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
use app\index\model\Classroom_time;
use think\facade\Session;
use app\index\model\Student;
use app\index\model\Seattable;
use think\Db;
use think\facade\Request;

/**
 * $teacherId = session('teacherId'); 
 * Request::action(); // 获取当前方法名
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-16 11:51:47
 */
class TeacherController extends TIndexController
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


        $termlength = $Term->length;
        $weeks = array();
        for ($i=0; $i < $termlength; $i++) { 
            $weeks[$i] = $i+1;
        }
        $this->assign('weeks',$weeks);

        //取出本学期的所有课程，编为一个数组
        $courses = $Term->Course;
        //储存所有课程的ID
        $AllcourseIds = [];
        foreach ($courses  as $value) {
          array_push($AllcourseIds, $value->id);
        }

        // 获取本教师id
        $teacherId = session('teacherId');

        //查询本学期本本教师的所有课程
        $Course = new Course();
        $getCourse =  $Course->where(['id'=>$AllcourseIds,'teacher_id'=>$teacherId])->select();

        //本学生本星期的课程表
        //前面是小节，后面是星期
        //0没用了！！！！！！！！！
        
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

        // 得到本学期本教师所有课程成绩所对应的course_id
        if(is_null($getCourse)) {
            $courseinfos = [];
        } else {
            //储存本学期本教师所有课程的course_id
            $CourseIds = [];
            foreach ($getCourse as $course) {
                array_push($CourseIds, $course->id);
            }

            $courseinfos = Courseinfo::where(['course_id'=>$CourseIds, 'week'=>$week])->select();

            foreach ($courseinfos as $key => $acourseinfo) {
                {
                    $coursetable[$acourseinfo->begin][$acourseinfo->weekday] = $acourseinfo;
                }
            }
            // dump($coursetable);
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
        $Course->number = $this->request->param('number/d');

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
        $Course->number = $this->request->param('number/d');
        
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
        $this->assign('isaction','course');
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
        // dump($classroom_id);
        // return;
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

        
        // dump($classroom_id);   
        // return ; 
        
        
        if (is_null($weeks)) {
            return $this->error('周次不能为空');
        }
        if (is_null($classroom_id)) {
            return $this->error('教室不能为空');
        }
        
        $begintest = $begin;
        $lengthtest = $length;
        if ($begintest < 5) {
            for ($i=2; $i < 5; $i++) { 
                if ($begintest -1 != 0) {
                    if (is_null(Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begintest-1)->where('length',$i)->find())) {
                        $begintest--;
                    }else{
                        return $this->error('更新课程失败，此课程之前的时段有课');
                    }
                }
            }
        }else if($begintest > 4 && $begintest < 9){
            for ($i=2; $i < 5; $i++) { 
                if ($begintest -1 != 4) {
                    if (is_null(Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begintest-1)->where('length',$i)->find())) {
                        $begintest--;
                    }else{
                        return $this->error('更新课程失败，此课程之前的时段有课');
                    }
                }
            }
        }


        $begintest = $begin;
        $lengthtest = $length;
        if ($begintest < 5) {
            while ($begintest < $begin+$length-1) {
                if (is_null(Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begintest+1)->find())) {
                    $begintest++;
                }else{
                    return $this->error('更新课程失败，此课程之后的时段有课');
                }
            }

        }else if($begintest > 4 && $begintest < 9){
            while ($begintest < $begin+$length-1) {
                if (is_null(Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$begintest+1)->find())) {
                    $begintest++;
                }else{
                    return $this->error('更新课程失败，此课程之后的时段有课');
                }
            }
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
        $table = Course::getStudentCourse(41,5,10,3);
        dump($table);
    }

    // 教师界面教室管理--李美娜
    // public function classroom()
    // {
    //     // 获取当前方法名
    //     $this->assign('isaction',Request::action());

    //     // 获取当前学期状态
    //     $ifterm = Term::ifterm();
    //     $this->assign('ifterm', $ifterm);

    //     // 实例化classroom
    //     $Classroom = new Classroom();
        
    //     // 获取查询信息
    //     $name = $this->request->get('name');

    //     $classrooms = $Classroom->where('classroomname', 'like', '%' . $name . '%')->paginate(5, false, [
    //         'query' =>[
    //             'name' => $name,
    //         ],
    //     ]);
        
    //     // 向V层传数据
    //     $this->assign('classrooms',$classrooms);

    //     // 取回打包后的数据
    //     $htmls = $this->fetch();

    //     // 将数据返回给用户
    //     return $htmls;
    // }


    // 教师模块教室管理插入教室--李美娜
    // public function classroomadd()
    // {
    //     return $this->fetch();
    // }
    
    // 教师模块教室管理插入教室--李美娜
    // public function classroomsave()
    // {
    //     // 接收传入数据
    //     $postData = $this->request->post();

    //     // 实例化Student空对象
    //     $Classroom = new Classroom();

    //     // 为对象赋值
    //     $Classroom->area_id = $postData['area_id'];
    //     $Classroom->classroomname = $postData['classroomname'];
    //     $Classroom->row = $postData['row'];
    //     $Classroom->column = $postData['column'];

    //     // 添加数据
    //     if (!$Classroom->save())
    //     {
    //         return $this->error('数据添加错误：' . $Classroom->getError());
    //     }
    //     return $this->success('操作成功', url('classroom'));
    // }


    // 教师模块教室管理删除教室--李美娜
    // public function classroomdelete()
    // {
    //     // 获取pathinfo传入的ID值
    //     $id = $this->request->param('id/d');

    //     if (is_null($id) || 0 === $id){
    //         return $this->error('未获取ID信息');
    //     }

    //     // 获取要删除的对象
    //     $Classroom = Classroom::get($id);

    //     // 要删除的对象存在
    //     if (is_null($Classroom)) {
    //         return $this->error('不存在id为' . $id . '的教室，删除失败');
    //     }

    //     // 删除对象
    //     if (!$Classroom->delete()) {
    //         return $this->error('删除失败:');
    //     }

    //     // 进行跳转
    //     return $this->success('删除成功',url('classroom'));
    // }

    // 教师界面教室管理编辑教室--李美娜
    // public function classroomedit()
    // {
    //     // 获取pathinfo传入的ID值
    //     $id = $this->request->param('id/d');

    //     // 在Student表模型中获取当前记录
    //     if (is_null($Classroom = Classroom::get($id))) {
    //         return $this->error('系统未找到ID为' . $id . '的记录',url('classroom'));
    //     }

    //     // 向V层传数据
    //     $this->assign('classroom', $Classroom);

    //     // 取回打包后的数据
    //     $htmls = $this->fetch();

    //     // 将数据返回给用户
    //     return $htmls;
    // }


    // 教师界面教室管理更新教室--李美娜
    // public function classroomupdate()
    // {
    //     try {
    //         // 接收数据，获取要更新的关键字信息
    //         $id = $this->request->post('id/d');
    //         $message = '更新成功';
    //         $Classroom = Classroom::get($id);

    //         if (!is_null($Classroom)) {
    //             // 写入要更新的数据
    //             // $Classroom->area_id  = $this->request->post('area_id');
    //             $Classroom->classroomname = $this->request->post('classroomname');
    //             $Classroom->row = $this->request->post('row');
    //             $Classroom->column = $this->request->post('column');

    //             // 更新
    //             if (false === $Classroom->save())
    //             {
    //                 $message =  '更新失败' . $Classroom->getError();
    //             }
    //         } else {
    //             throw new \Exception("所更新的记录不存在", 1);  
    //         }
    //     } catch (\Exception $e) {
    //         $message = $e->getMessage();
    //     }

    //     // 进行跳转
    //     return $this->success($message,url('classroom'));
    // }

    //教师模块上课模式——刘宇轩
    public function online() 
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());


        $teacher = Teacher::where('id',Session::get('teacherId'))->find(); //获取教师信息
        //获取当天的所有课程
        $allcourseinfo = Courseinfo::where('weekday',Term::weekday())->where('week',Term::getWeek())->order('begin')->select(); 

        $klass = [];
        $courseinfo = [];    

        //依次判断每节课是否为本教师的课程
        foreach ($allcourseinfo as $key => $acourse) {
            if ($acourse->course->teacher_id == Session::get('teacherId')) {
                $courseinfo[$key] = $acourse;
                $klasses = KlassCourse::where('course_id',$acourse->course->id)->select();
                $str = "";
                //对于每个班级，用ID取出班级名称，并连接字符串
                foreach ($klasses as $aclass) {
                    $str1 = klass::get($aclass->klass_id)->name;
                    $str = $str.",".$str1; 
                }
                //把字符串信息合并到课程信息中
                $klass[$key] = substr($str,1);
                }
        }
        // dump($courseinfo);
        // dump($klass);
        // return;

        // echo Term::weekday();
        // echo Term::getWeek();
        $this->assign('courseinfo',$courseinfo);
        $this->assign('klass',$klass);

    //     var_dump(Db::table('yunzhi_classroom') ->field('id')
    // ->group('id')
    // ->select());
        // dump($allcourseinfo);
        // return;
    	return $this->fetch();
    }

    //教师模块课程首页——刘宇轩
    public function onlinehome()
    {
        // 获取当前方法名
        $this->assign('isaction',Request::action());

        $id = $this->request->param('id/d');

        $courseinfo = Courseinfo::where('id', $id)->find();
        $this->assign('courseinfo',$courseinfo);

        $students = Oncourse::where('courseinfo_id',  $id)->order(['row', 'column'=>'asc'])->select();
        $this->assign('students', $students);
        // dump($students);
        // return ;

        // 人数比
        $nownumber = count($students);
        $this->assign('nownumber', $nownumber);
        $courseinfo = Courseinfo::get($id);
        $this->assign('number', $courseinfo->Course->number);
        
        // 传给v层一个变量，初始化为0
        $temp = 0;
        $this->assign('temp',$temp);
        return $this->fetch();
    }

    //教师模块签到界面——刘宇轩 
    public function setcourse()
    {
        $courseinfo = Courseinfo::where('id',$this->request->param('id/d'))->find();
        // $classroom[0] = $courseinfo->classroom->area_id . $courseinfo->classroom->classroomname;
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
        $students = Oncourse::where('courseinfo_id', $this->request->param('id'))->select();
        // dump($students);
        // return ;
        if (count($students) == 0) {
            
            return $this->error('未开启签到',url('online'));
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
        $this->assign('courseinfo',$courseinfo);
        $this->assign('student',$thestudent);

        return $this->fetch();
    }
    
    // 教师对学生回答结果进行评价——赵凯强
    public function onlineassess()
    {
        $assess = $this->request->param('respond');
        $studentid = $this->request->param('studentid');
        $courseinfoid = $this->request->param('courseinfoid');

        if ($assess == 1){
            $oncourse = Oncourse::where('courseinfo_id', $courseinfoid)->where('student_id', $studentid)->find();
            $oncourse->respond++;
            
            $courseinfo = Courseinfo::get($courseinfoid);
            $score = Score::where('course_id', $courseinfo->course_id)->where('student_id', $studentid)->find();
            $score->responds++;

            if ($oncourse->save() && $score->save()) {
                return $this->success('评价成功',url('onlinesignin?id='.$courseinfoid));  
            } else {
                return $this->error('评价失败',url('onlinesignin?id='.$courseinfoid));
            }
        }

    return $this->success('评价成功',url('onlinesignin?id='.$courseinfoid));   
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
        $courses = Course::where('teacher_id',$id)->paginate(10);

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
            $acourse->klass = substr($str,1);

        }
        // dump ($courses);
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
        $oncourse = new Oncourse;
        $arrival1 = $oncourse->where('courseinfo_id',$id)->where('arrival',1)->select();
        $arrival0 = $oncourse->where('courseinfo_id',$id)->where('arrival',0)->select();
        $this->assign('arrival1',$arrival1);
        $this->assign('arrival0',$arrival0);
        $this->assign('courseinfo',$courseinfo);
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
        // dump($scores);
        // return;
        $this->assign('course',$course);
        $this->assign('scores',$scores);
        return $this->fetch();
        // dump($course);
        // dump($scores);
        // return;
    }
    //获取前台传过来的平时成绩数据并保存到数据表中
    public function usualScore() {
        
       $data = Request::instance()->param();
       $id = $data['id'];
       $value = $data['usualvalue'];
       $Score = Score::get($id);
       $Score->usual_score = $value;
       $Score->save();
       
    }
     //获取前台传过来的考试成绩数据并保存到数据表中
    public function examScore() {
        
       $data = Request::instance()->post();
       dump($data);
       $id = $data['id'];
       $value = $data['examvalue'];
       $Score = Score::get($id);
       $Score->exam_score = $value;
       $Score->save();
       
    }
    //获取前台传过来的总成绩数据并保存到数据表中
     public function totalScore() {
        
       $data = Request::instance()->post();
       dump($data);
       $id = $data['id'];
       $value = $data['totalValue'];
       $Score = Score::get($id);
       $Score->total_score = $value;
       $Score->save();
       
    }
    //获取前台传入的平时成绩的权重值，计算出考试成绩的权重值后返回给前台
    public function getWeight()
    {
    $usualScore = Request::instance()->param('usualScore');
    $examScore = 100-($usualScore*100).'%';    
    return $examScore;
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
                $score->usual_score = $scores["usual_score"][$i];
                $score->exam_score = $scores["exam_score"][$i];
                $score->total_score = (int)$scores["total_score"][$i];
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

    //教师扫码查看签到情况
    public function OnlineSee()
    {
        // 获取当前方法名
        //$this->assign('isaction',Request::action());

        //获取小节
        $littleClass =Term::littleClass();
        //获取周次
        $week = Term::getWeek();
        //星期
        $weekday = Term::weekday();
        //获取激活学期
        $Term = Term::where('state',1)->find();
        if($Term == null){
            return $this->error('当前学期已结束或未激活');
        }

        //取出本学期的所有课程，编为一个数组
        $courses = $Term->Course;
        //储存所有课程的ID
        $AllcourseIds = [];
        foreach ($courses  as $value) {
          array_push($AllcourseIds, $value->id);
        }

        // 获取本教师id
        $teacherId = session('teacherId');

        //查询本学期本本教师的所有课程
        $Course = new Course();
        $getCourse =  $Course->where(['id'=>$AllcourseIds,'teacher_id'=>$teacherId])->select();
        //得到本学期本教师所有课程成绩所对应的course_id
        if(is_null($getCourse)) {
            $courseinfo = null;
        } else {
            //储存本学期本教师所有课程的course_id
            $CourseIds = [];
            foreach ($getCourse as $course) {
                array_push($CourseIds, $course->id);
            }
            $begins=[$littleClass,$littleClass-1];
            $courseinfo = Courseinfo::where(['course_id'=>$CourseIds, 'week'=>$week, 'weekday'=>$weekday, 'begin'=>$begins])->find();

            // foreach ($courseinfos as $key => $acourseinfo) {
            //     {
            //         $coursetable[$acourseinfo->begin] = $acourseinfo;
            //     }
            // }
        }
        //发送课程信息
        $this->assign('courseinfo',$courseinfo);
        // $this->assign('coursetable',$coursetable);
        if ($courseinfo == null) {
            $ifcourse = 0;
        }else{
            $ifcourse = 1;
        }
        // dump($ifcourse);
        // dump($coursetable);
        // return;
        //发送是否有课
        $this->assign('ifcourse',$ifcourse);


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

    //使用js调用该方法，进行绑定
    public function binding()
    {
        $Classroom_time = Classroom_time::where('id',$this->request->param('Classroom_time'))->find();
        $num = 0;//计数
        $courseinfo = courseinfo::where('id',$this->request->param('courseinfo'))->find();
        $course = $courseinfo->course;
        $Classroomtimeids = [];
        for ($i=$courseinfo->begin; $i < $courseinfo->begin + $courseinfo->length; $i++) { 
            $_Classroom_time = Classroom_time::where('id',$Classroom_time->id + $num)->find();
            $_Classroom_time->status = 1;
            $_Classroom_time->courseinfo_id = $courseinfo->id;
            array_push($Classroomtimeids, $_Classroom_time->id);
            $_Classroom_time->save();
            $num++;
        }

        $Seattables = Seattable::where(['classroom_time_id'=>$Classroomtimeids])->select();
        foreach ($Seattables as $key => $Seat) {
            $_score = Score::where('student_id',$Seat->student_id)->where('course_id',$course->id)->find();
            $_score->arrivals++;
            $_score->save();
        }
        return 1;
    }
}

