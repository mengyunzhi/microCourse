<?php
namespace app\index\controller;
use app\index\model\Term;
use app\index\model\classroom;
use think\request;
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:43:05
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-14 10:49:32
 */
class AdminController extends IndexController
{

	public function page()
	{
		return $this->fetch();
	}

	public function term()
	{
		$Term = new Term;
		$Terms = $Term->select();
		$this->assign('Term',$Terms);
		return $this->fetch();
	}

	//管理员模块学期管理插入学期——刘宇轩
	public function terminsert()
	{
		$postData = $this->request->post();
		$Term = new Term();
		$Term->name = $postData['name'];
		$Term->start = $postData['start'];
		$Term->end = $postData['end'];
		$Term->state = 0;
        $result = $Term->save($Term->getData());
        if (false === $result)
        {
            return '新增失败:' . $Term->getError();
        } else {
            return $this->success('新增成功。新增ID为:' . $Term->id,url('term'));
        }
	}

	//管理员模块学期管理删除学期——刘宇轩
	public function termdelete()
	{
		$id = $this->request->param('id/d');
		if (is_null($id) || 0 === $id){
			return $this->error('未获取ID信息');
		}
		$Term = Term::get($id);
        if (is_null($Term)) {
            return $this->error('不存在id为' . $id . '的教师，删除失败');
        }
        if (!$Term->delete()) {
            return $this->error('删除失败:');
        }
		return $this->success('删除成功',url('term'));
	}

	//管理员模块学期管理编辑学期——刘宇轩
	public function termedit()
	{
		$id = $this->request->param('id/d');
        $Term = Term::get($id);
        $this->assign('Term', $Term);
        $htmls = $this->fetch();
        return $htmls;
	}

	//管理员模块学期管理更新学期——刘宇轩
	public function termupdate()
	{
	    try {
            $id = $this->request->post('id/d');
            $message = '更新成功';
            $Term = Term::get($id);

            if (!is_null($Term)) {
                $Term->name = $this->request->post('name');
                $Term->start = $this->request->post('start');
                $Term->end = $this->request->post('end');
                $Term->length = $this->request->post('length');
                if (false === $Term->save())
                {
                    $message =  '更新失败' . $Term->getError();
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);  
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return $this->success($message,url('term'));
	}

	//管理员模块学期管理添加学期——刘宇轩
	public function termadd()
	{
		return $this->fetch();
	}

	//管理员模块学期管理激活、冻结学期——刘宇轩
	public function termcheck()
	{
		$id = $this->request->param('id/d');
		if (is_null($id) || 0 === $id){
			return $this->error('未获取ID信息');
		}
		$Term = Term::get($id);
        if ($Term->state == 0) {
            $Term->state = 1;
        } else {
        	$Term->state = 0;
        }
        if (false === $Term->save())
        {
            $message =  '状态切换失败';
        }
		return $this->success('状态切换成功',url('term'));
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

	public function student()
	{
		return $this->fetch();
	}

	public function adminstudentadd()
	{
		return $this->fetch();
	}

	public function adminstudentedit()
	{
		return $this->fetch();
	}

	//管理员模块教室管理——刘宇轩
	public function classroom()
	{
		$classroom = new classroom;
		$classroom = $classroom->select();
		$this->assign('classroom',$classroom);
		return $this->fetch();
	}

	//管理员模块教室管理添加教室——刘宇轩
	public function classroomadd()
	{
		return $this->fetch();
	}

	//管理员模块教室管理编辑教室——刘宇轩
	public function classroomedit()
	{
		$id = $this->request->param('id/d');
        $classroom = classroom::get($id);
        $this->assign('classroom', $classroom);
        $htmls = $this->fetch();
        return $htmls;
	}

	//管理员模块教室管理插入教室——刘宇轩
	public function classroominsert()
	{
		$postData = $this->request->post();
		$classroom = new classroom();
		$classroom->classroomplace = $postData['classroomplace'];
		$classroom->classroomname = $postData['classroomname'];
		$classroom->row = $postData['row'];
		$classroom->column = $postData['column'];
        $result = $classroom->save($classroom->getData());
        if (false === $result)
        {
            return '新增失败:' . $classroom->getError();
        } else {
            return $this->success('新增成功。新增ID为:' . $classroom->id,url('classroom'));
        }
	}

	//管理员模块教室管理删除教室——刘宇轩
	public function classroomdelete()
	{
		$id = $this->request->param('id/d');
		if (is_null($id) || 0 === $id){
			return $this->error('未获取ID信息');
		}
		$classroom = classroom::get($id);
        if (is_null($classroom)) {
            return $this->error('不存在id为' . $id . '的教室，删除失败');
        }
        if (!$classroom->delete()) {
            return $this->error('删除失败:');
        }
		return $this->success('删除成功',url('classroom'));
	}


	//管理员模块教室管理更新教室——刘宇轩
	public function classroomupdate()
	{
	    try {
            $id = $this->request->post('id/d');
            $message = '更新成功';
            $classroom = classroom::get($id);

            if (!is_null($classroom)) {
                $classroom->classroomplace = $this->request->post('classroomplace');
                $classroom->classroomname = $this->request->post('classroomname');
                $classroom->row = $this->request->post('row');
                $classroom->column = $this->request->post('column');
                if (false === $classroom->save())
                {
                    $message =  '更新失败' . $classroom->getError();
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);  
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return $this->success($message,url('classroom'));
	}
}