<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Student;
use app\index\model\Term;
use app\index\model\Klass;
use app\index\model\KlassCourse;
use app\index\model\Course;
use app\index\model\Teacher;
use app\index\model\Score;


/**
 * $studentId = session('studentId');  //得到本学生Id
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-22 15:09:20
 */


class StudentController extends SIndexController
{	
	public function page()
	{
		return $this->fetch();
	}

	public function online()
	{
		return $this->fetch();
	}

	// 学生界面的课程查询--李美娜
	public function course()
	{
		// 查找已激活的学期，主要用于状态栏的显示
		$term = Term::where('state',1)->find();	
		if ($term === null) {
			$termid = 0;
		} else {
			$termid = $term->id;
		}		
		$this->assign('termid', $termid);

		// 获取所有学期
		$terms = Term::paginate();
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
						
		// 先默认获取ID为1的学生所对应的课 此处默认score表的数据是自动写入的！！！
		$ids = '1';
		$student = Student::get('$ids');
		$courses = [];
        if (is_null($course = Score::where('student_id',$ids)->select())) {
            $this->assign('courses', $courses);
        } else {
        	foreach ($course as $acourse) {       		
           		if ($acourse->Course->term_id == $id) {
           			array_push($courses, $acourse->Course);  // 该生的选修课和必修课
           		}
        	}       	
        }
        // $course = $courses->where('term_id',$Term_id);  // 获取请求学期的课程
        $this->assign('courses', $courses);

        // 取回打包后的数据，显示给用户
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

	public function seat()
	{
		return $this->fetch();
	}
    
    // 学生成绩查询——赵凯强
	public function score()
	{
		$terms = Term::paginate();
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
        
        $studentId = session('studentId');
        $Term = Term::get($id);
        $this->assign('Term', $Term);
        $courses = $Term->Course;
        $score = new Score();
        $scores = [];
        foreach ($courses as $key => $course) {
            if (!is_null($scoree = $score->where('course_id',$course->id)->where('student_id',$studentId)->find())){
                array_push($scores, $scoree);
            }
        	
        }

        $this->assign('scores', $scores);
        return $this->fetch();
	}

    // 学生信息页面————赵凯强
	public function info()
	{
		$id = session('studentId');;
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
      $klasses = Klass::paginate();
      $this->assign('klasses', $klasses);
      return $this->fetch();
    }
    // 学生信息编辑保存————赵凯强
    public function infoupdate()
    {
    	$id = $this->request->param('id/d');

    	// 获取传入的学生信息
    	$Student = Student::get($id);
    	if (is_null($Student)) {
    		return $this->error('系统未找到ID为' . $id . '的记录');
    	}

    	// 数据更新
    	$Student->password = $this->request->param('password');
    	$Student->klass_id = $this->request->param('klass_id/d');
        if (!$Student->save()) {
        	return $this->error('更新错误：' . $Student->getError());
        } else {
        	return $this->success('操作成功', url('info'));
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
}
