<?php
namespace app\index\controller;
use app\index\model\Teacher;
use think\Request;     // 引用Request

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

	public function student()
	{
		return $this->fetch();
	}

	public function studentadd()
	{
		return $this->fetch();
	}

	public function studentedit()
	{
		return $this->fetch();
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