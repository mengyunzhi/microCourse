<?php
namespace app\index\controller;
use app\index\model\Student; 
use app\index\model\Teacher;
use think\Request;     // 引用Request


use think\Request;  //请求
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:43:05
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 09:46:38
 */
class AdminController extends IndexController	
{

	public function page()
	{
		 return $this->fetch();
		
	}

	public function term()
	{
		return $this->fetch();
	}

	public function termedit()
	{
		return $this->fetch();
	}

	public function termadd()
	{
		return $this->fetch();
	}

	public function teacher()
	{
		$Teacher = new Teacher;
		$teachers = $Teacher->select();
		 
		// 向v层传数据
		$this->assign('teachers', $teachers);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;
	}
    
    // 新增数据保存方法
	public function teachersave()  // 数据增加
	{
		// 接收传入数据
		$postData = $this->request->param();
		
		// 实例化Teacher空对象
        $Teacher = new Teacher();
        

		// 为对象赋值
		$Teacher->name = $postData['name'];
		$Teacher->num = $postData['num'];
		$Teacher->sex = $postData['sex'];
		$Teacher->password = $postData['password'];

        // 添加数据
        if (!$Teacher->save()) {
        	return $this->error('数据添加错误：' . $Teacher->getError());
        }
        return $this->success('操作成功', url('teacher'));
	}

    //  teacher导入增加页面
	public function teacheradd()
	{
		return $this->fetch();
	}
    
    // teacher删除方法
	public function teacherdelete()
	{
		// 获取传入的值
		$id = $this->request->param('id/d');

		if(is_null($id) || 0 === $id) {
			return $this->error('未获取到ID信息');
		}

		// 获取要删除的对象
		$Teacher = Teacher::get($id);

		// 要删除的对象不存在
		if (is_null($Teacher)) {
			return $this->error('不存在id为' . $id . '的教师，删除失败');
		}

		// 删除对象
		if (!$Teacher->delete()) {
			return $this->success('删除成功', url('teacher'));
		}
		// 进行跳转
		return $this->success('删除成功', url('teacher'));
	}

    // Teacher编辑将原数据传入编辑页面
	public function teacheredit()
	{
		// 获取传入ID
		$id = $this->request->param('id/d');

		// 在Teacher表模型中获取当前记录
		if(is_null( $Teacher = Teacher::get($id))) {
			return '系统未找到ID为' . $id .'的记录';
		}

		// 将数据传给v层
		$this->assign('Teacher', $Teacher);

		// 获取封装好的v层内容
        $htmls = $this->fetch();

		// 将封装好的v层内容返回给用户
		return $htmls;
	}

	// teacher更新
	public function teacherupdate()
	{
		// 接收数据，获取要更新的关键字信息
		$id = $this->request->param('id/d');

		// 获取当前对象
		$Teacher = Teacher::get($id);
		if (is_null($Teacher)) {
			return $this->error('系统未找到ID为' . $id . '的记录');
		}

		// 写入要更新的数据
		$Teacher->name = $this->request->param('name');
		$Teacher->num = $this->request->param('num');
		$Teacher->sex = $this->request->param('sex/d');
		$Teacher->password = $this->request->param('password');

		// 更新
		if(!$Teacher->save()) {
			return $this->error('更新错误：' . $Teacher->getError());
		} else {
			return $this->success('操作成功', url('teacher'));
		}
	}

	// 管理员界面学生管理--李美娜
	public function student()
	{
		$Student = new Student();
        $students = $Student->select();

        // 向V层传数据
        $this->assign('students',$students);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
	}

	// 管理员界面学生的增加--李美娜
	public function studentadd()
	{
		return $this->fetch();
	}

	// 管理员界面学生的保存--李美娜
	public function studentsave()
	{
		// 接收传入数据
		$postData = $this->request->post();
		
		// 实例化Student空对象
		$Student = new Student();
	
		// 为对象赋值
		$Student->name = $postData['name'];
		$Student->num = $postData['num'];
		$Student->sex = $postData['sex'];
		$Student->academy = $postData['academy'];
		$Student->major = $postData['major'];
		$Student->klass_id = $postData['klass_id'];
		
		// 添加数据
		if (!$Student->save()) {
			return $this->error('数据添加错误：' . $Student->getError());
		}

		return $this->success('操作成功', url('student'));
		
	}

	// 管理员学生的删除--李美娜
	public function studentdelete()
	{
		// 获取pathinfo传入的ID值
		$id = $this->request->param('id/d');  // "/d"表示将数值转化为“整型”

		if (is_null($id) || 0 === $id) {
			return $this->error('未获取到ID信息');
		}

		// 获取要删除的对象
		$Student = Student::get($id);

		// 要删除的对象存在
		if (is_null($Student)) {
			return $this->error('不存在id为' . $id .'的学生，删除失败');
		}
	
		// 删除对象
		if ($Student->delete()) {
			return $this->error('删除成功:' . $Student->getError());
			}

		// 进行跳转
		return $this->success('删除成功', url('student'));
	}

	//管理员学生的编辑--李美娜
	public function studentedit()
	{
		// 获取pathinfo传入的ID值
		$id = $this->request->param('id/d');  // "/d"表示将数值转化为“整型”

		// 在Student表模型中获取当前记录
		if (is_null($Student = Student::get($id))) {
			return '系统未找到ID为' . $id . '的记录'; 
		}

		// 向V层传数据
        $this->assign('Student',$Student);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
	}

	//管理员学生编辑数据的更新--李美娜
	public function studentupdate()
	{
		// 接收数据，获取要更新的关键字信息
        $id = $this->request->param('id/d');  // "/d"表示将数值转化为“整型”

        // 获取当前对象
        $Student = Student::get($id);

        // 写入要更新的数据
        $Student->name = $this->request->post('name');
        $Student->num = $this->request->post('num');
        $Student->sex = $this->request->post('sex/d');
        $Student->academy = $this->request->post('academy');
        $Student->major = $this->request->post('major');
        $Student->klass_id = $this->request->post('klass_id');

        // 更新
        $message = '更新成功';
        if (false === $Student->save()) {
            $message =  '更新失败' . $Student->getError();
        }

        // 进行跳转
		return $this->success($message, url('student'));
	}

	public function classroom()
	{
		return $this->fetch();
	}

	public function classroomadd()
	{
		return $this->fetch();
	}

	public function classroomedit()
	{
		return $this->fetch();
	}

}