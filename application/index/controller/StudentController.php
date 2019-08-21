<?php
namespace app\index\controller;
use app\common\model\Index;
use think\Controller;
use app\index\model\Term;
use app\index\model\Course;
use app\index\model\Score;

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-19 17:14:40
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

	public function info()
	{
		return $this->fetch();
	}	
    
    public function infoedit()
    {
      return $this->fetch();
    }	

    public function password()
    {
      return $this->fetch();
    }

    public function aboutourteam()
    {
      return $this->fetch();
    }
}