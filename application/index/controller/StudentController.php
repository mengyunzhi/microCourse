<?php
namespace app\index\controller;
use app\common\model\Index;
use think\Controller;
use think\Db;
use app\index\model\Student;
use app\index\model\Term;
use app\index\model\Klass;
use app\index\model\KlassCourse;
use app\index\model\Course;
use app\index\model\Teacher;
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

	public function score()
	{
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