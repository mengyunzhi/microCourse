<?php
namespace app\index\controller;
use app\index\model\Course;
use app\index\model\Term;
use app\index\model\Academy;
use app\index\model\Klass;
use app\index\model\Teacher;
use app\index\model\KlassCourse;
use think\Request;     // 引用Request
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 09:46:15
 */
class TeacherController extends IndexController
{

	public function teacherpage()
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

    public function teachercoursetime()
    {
    	return $this->fetch();
    }

    public function teacherclassroom()
    {
    	return $this->fetch();
    }

    public function teacheronline()
    {
    	return $this->fetch();
    }

    public function teacherincourse()
    {
    	return $this->fetch();
    }

    public function teacherincourse2()
    {
    	return $this->fetch();
    }

    public function teachergrade()
    {
    	return $this->fetch();
    }

    public function teachercourseedit()
    {
    	return $this->fetch();
    }

    public function teacherclassroomedit()
    {
    	return $this->fetch();
    }

}