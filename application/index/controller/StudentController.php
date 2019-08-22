<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Term;
use app\index\model\Course;
use app\index\model\Score;
use app\index\model\Student;
use app\index\model\Klass;


/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-22 15:09:20
 */
class StudentController extends IndexController
{	
	public function page()
	{
		return $this->fetch();
	}

	public function online()
	{
		return $this->fetch();
	}

	public function course()
	{
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

        $Term = Term::get($id);
        $this->assign('Term', $Term);
        $courses = $Term->Course;
        $score = new Score();
        $scores = [];
        foreach ($courses as $key => $course) {
        	array_push($scores, $score->where('course_id',$course->id)->where('student_id',1)->find());
        }

        $this->assign('scores', $scores);
        return $this->fetch();
	}

    // 学生信息页面————赵凯强
	public function info()
	{
		$id = 1;
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
