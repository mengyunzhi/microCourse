<?php
namespace app\index\controller;
use app\index\model\Student; 
      // 学生模型

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
		return $this->fetch();
	}

	public function teacheradd()
	{
		return $this->fetch();
	}

	public function teacheredit()
	{
		return $this->fetch();
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