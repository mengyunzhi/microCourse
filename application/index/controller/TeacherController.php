<?php
namespace app\index\controller;
use app\index\model\Classroom;
use think\Request;     // 引用Request
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:37
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 09:46:15
 */
class TeacherController extends IndexController
{
	public function page()
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

    // 教师界面教室管理--李美娜
    public function classroom()
    {
    	$pageSize = 5; // 每页显示5条数据

        // 实例化classroom
        $Classroom = new Classroom();

        // 调用分页
        $classrooms = $Classroom->paginate($pageSize);
        $page = $classrooms->render();

        // 向V层传数据
        $this->assign('classrooms',$classrooms);
        $this->assign('page',$page);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }

    // 教师界面教室添加教室--李美娜
    public function classroomadd()
    {
        return $this->fetch();
    }
    
    // 教师模块教室管理插入教室--李美娜
    public function classroomsave()
    {
        // 接收传入数据
        $postData = $this->request->post();

        // 实例化Student空对象
        $Classroom = new Classroom();

        // 为对象赋值
        $Classroom->classroomplace = $postData['classroomplace'];
        $Classroom->classroomname = $postData['classroomname'];
        $Classroom->row = $postData['row'];
        $Classroom->column = $postData['column'];

        // 添加数据
        if (!$Classroom->save())
        {
            return $this->error('数据添加错误：' . $Classroom->getError());
        }
        return $this->success('操作成功', url('classroom'));
    }

    // 教师模块教室管理删除教室--李美娜
    public function classroomdelete()
    {
        // 获取pathinfo传入的ID值
        $id = $this->request->param('id/d');

        if (is_null($id) || 0 === $id){
            return $this->error('未获取ID信息');
        }

        // 获取要删除的对象
        $Classroom = Classroom::get($id);

        // 要删除的对象存在
        if (is_null($Classroom)) {
            return $this->error('不存在id为' . $id . '的教室，删除失败');
        }

        // 删除对象
        if (!$Classroom->delete()) {
            return $this->error('删除失败:');
        }

        // 进行跳转
        return $this->success('删除成功',url('classroom'));
    }

    // 教师界面教室管理编辑教室--李美娜
    public function classroomedit()
    {
        // 获取pathinfo传入的ID值
        $id = $this->request->param('id/d');

        // 在Student表模型中获取当前记录
        if (is_null($Classroom = Classroom::get($id))) {
            return $this->error('系统未找到ID为' . $id . '的记录',url('classroom'));
        }

        // 向V层传数据
        $this->assign('classroom', $Classroom);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }

    // 教师界面教室管理更新教室--李美娜
    public function classroomupdate()
    {
        try {
            // 接收数据，获取要更新的关键字信息
            $id = $this->request->post('id/d');
            $message = '更新成功';
            $Classroom = Classroom::get($id);

            if (!is_null($Classroom)) {
                // 写入要更新的数据
                $Classroom->classroomplace = $this->request->post('classroomplace');
                $Classroom->classroomname = $this->request->post('classroomname');
                $Classroom->row = $this->request->post('row');
                $Classroom->column = $this->request->post('column');

                // 更新
                if (false === $Classroom->save())
                {
                    $message =  '更新失败' . $Classroom->getError();
                }
            } else {
                throw new \Exception("所更新的记录不存在", 1);  
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        // 进行跳转
        return $this->success($message,url('classroom'));
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

    public function grade()
    {
    	return $this->fetch();
    }

    public function courseedit()
    {
    	return $this->fetch();
    }
}