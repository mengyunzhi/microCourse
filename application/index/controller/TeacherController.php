<?php
namespace app\index\controller;
use app\index\model\Course;
use app\index\model\Term;
use app\index\model\Academy;
use app\index\model\Klass;
use app\index\model\Teacher;
use app\index\model\KlassCourse;
use think\Request;     // 引用Request
use app\common\model\Index;
use app\index\model\oncourse;
use app\index\model\courseinfo;
use app\index\model\score;
use think\Controller;
use app\index\model\Classroom;

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-19 17:19:20
 */
class TeacherController extends IndexController
{
	public function page()
	{
        return $this->fetch();
	}	
    

    // 课程——赵凯强
    public function course()
    {
        $courses = Course::paginate();
        $this->assign('courses', $courses);
    	return $this->fetch();
    }
    
    // 课程增加跳转——赵凯强
    public function courseadd()
    {
        $terms = Term::paginate();
        $this->assign('terms', $terms);
        $academys = Academy::paginate();
        $this->assign('academys', $academys);
        $teachers = Teacher::paginate();
        $this->assign('teachers', $teachers);
        $klass = new Klass;
        $this->assign('Klasses', $klass->select());
        $this->assign('Course', new Course);
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
            return $this->error('保存错误：' . $Course->getError());
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
        
        $terms = Term::paginate();
        $this->assign('terms', $terms);
        $teachers = Teacher::paginate();
        $this->assign('teachers', $teachers);
        $klass = new Klass;
        $this->assign('Klasses', $klass->select());

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
        if (is_null($Course->save())) {
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
    
    // 课程上课查看跳转——赵凯强
    public function coursesee()
    {
        return $this->fetch();
    }
    
    // 课程上课查看跳转二级页面——赵凯强
    public function courseweek()
    {
        return $this->fetch();
    }

    public function coursetime()
    {
    	return $this->fetch();
    }

    // 教师界面教室管理--李美娜
    public function classroom()
    {
    	$pageSize = 5; // 每页显示5条数据

        // 实例化classroom
        $Classroom = new Classroom();

        // 调用分页
        $classrooms = $Classroom->paginate($pageSize);
        $page = $classrooms->render();

        // 向V层传数据
        $this->assign('classrooms',$classrooms);
        $this->assign('page',$page);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }


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

    public function online()
    {
    	return $this->fetch();
    }


    //教师模块成绩录入界面——刘宇轩
    public function grade()
    {
    	$course = new course;
        $courses = $course->select();//取出全部课程

        $klass = new Klass;
        $klasscourse = new klasscourse;
        $klasscourses = $klasscourse->select();//取出班级课程中间表的全部信息
        
        //对于每个课程，取出对应的多个班级信息
        foreach ($courses as $acourse) {
            $klasses = $klasscourse->where('course_id',$acourse->id)->select();
            $str = "";
            //对于每个班级，用ID取出班级名称，并连接字符串
            foreach ($klasses as $aclass) {
                $str1 = klass::get($aclass->klass_id)->name;
                $str = $str." ".$str1; 
            }
            //把字符串信息合并到课程信息中
            $acourse->klass = $str;
        }
        //发送课程信息
        $this->assign('course',$courses);
        return $this->fetch();
    }

    //教师模块成绩录入二级界面课程详情——刘宇轩
    public function gradeinfo()
    {
        $id = $this->request->param('id/d');
        $courses = new course();
        $course = course::get($id);
        $info = $courses->courseinfo()->where('course_id',$id)->select();
        $this->assign('course',$course);
        $this->assign('info',$info);
        return $this->fetch();
    }

    //教师模块成绩录入三级界面出勤详情——刘宇轩
    public function gradeoncourse()
    {
        $id = $this->request->param('id/d');
        $courseinfo = courseinfo::get($id);
        $course = $courseinfo->course()->find();
        $oncourse = new oncourse;
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
        $id = $this->request->param('id/d');
        $course = course::get($id);
        $score = new score;
        $scores = $score->where('course_id',$id)->select();
        $this->assign('course',$course);
        $this->assign('score',$scores);
        return $this->fetch();
    }

    //教师模块成绩更新-刘宇轩
    public function gradeupdate()
    {
        $scores = $this->request->post();
        $key = $this->request->post('key');
        //dump ($key);
        //dump ($scores);
        //dump ($scores["id"]["0"]);
        $message = '更新成功';
        for ($i=0; $i < $key - 1; $i++) { 
            $score = score::get($scores["id"][$i]);
            if (!is_null($score)){
                $score->score1 = $scores["score1"][$i];
                $score->score2 = $scores["score2"][$i];
                $score->scoresum = $scores["scoresum"][$i];
                if (false === $score->save())
                {
                    $message = '更新失败' . $score->getError();
                }
            }else{
                throw new \Exception("所更新的记录不存在", 1);
            }

            //dump ($score);
        }
        return $this->success($message,url('grade'));
    }

}

