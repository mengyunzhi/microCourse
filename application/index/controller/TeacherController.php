<?php
namespace app\index\controller;
use app\common\model\Index;
use app\index\model\course;
use app\index\model\KlassCourse;
use app\index\model\Klass;
use app\index\model\oncourse;
use app\index\model\courseinfo;
use app\index\model\score;
use think\Controller;
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-19 11:44:58
 */
class TeacherController extends IndexController
{

	public function teacherpage()
	{
		return $this->fetch();
	}	
    
    public function teachercourse()
    {
    	return $this->fetch();
    }

    public function teachercoursetime()
    {
    	return $this->fetch();
    }

    public function teacherclassroom()
    {
    	return $this->fetch();
    }

    public function online()
    {
    	return $this->fetch();
    }

    public function incourse()
    {
    	return $this->fetch();
    }

    public function incourse2()
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

    public function courseedit()
    {
    	return $this->fetch();
    }

    public function teacherclassroomedit()
    {
    	return $this->fetch();
    }

}