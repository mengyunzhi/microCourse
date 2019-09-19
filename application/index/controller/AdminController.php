<?php
namespace app\index\controller;
use app\index\model\Term;
use app\index\model\Classroom;
use app\index\model\Student; 
use app\index\model\Teacher;
use app\index\model\Klass;
use think\facade\Request;
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:43:05
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-14 10:49:32
 */
class AdminController extends AIndexController	
{

	public function page()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		 return $this->fetch();
		
	}

	public function term()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);
        
        $nowweek = Term::getWeek();
        $this->assign('nowweek', $nowweek);

		$Term = new Term;
		$Terms = $Term->paginate(5);
		$page = $Terms->render();
		$this->assign('Term',$Terms);
		$this->assign('page', $page);
		return $this->fetch();
	}

	//管理员模块学期管理插入学期——刘宇轩--李美娜
	public function termsave()
	{
		// 接收传入的数据
		$postData = $this->request->post();

		// 实例化Term空对象
		$Term = new Term();

		// 为对象赋值
		$Term->name = $postData['name'];
		$Term->start = $postData['start'];
		$Term->end = $postData['end'];
		$Term->state = 0;
		$Term->length = $postData['length'];

		// 添加数据
        if (!$Term->save())
        {
            return $this->error('数据添加错误：' . $Term->getError());
        }
        return $this->success('操作成功', url('term'));
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
            return $this->error('不存在id为' . $id . '的学期，删除失败');
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
        if ($Term->state == 0&& Term::TermLength()==0) {
            $Term->state = 1;
            if (false === $Term->save()) {
	            $message =  '状态切换失败';
	        }
			return $this->success('状态切换成功',url('term'));
        } else if ($Term->state == 1){
        	$Term->state = 0;
        	if (false === $Term->save())
	        {
	            $message =  '状态切换失败';
	        }
			return $this->success('状态切换成功',url('term'));
        } else {
        	return $this->error('状态切换失败');
        }
	}

	public function teacher()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		$Teacher = new Teacher;
		$teachers = $Teacher->paginate(5);
		$page  = $teachers->render();
		 
		// 向v层传数据
		$this->assign('teachers', $teachers);
		$this->assign('page', $page);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;
	}
    
    //  teacher导入增加页面
	public function teacheradd()
	{
		return $this->fetch();
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

	// 管理员界面班级管理————赵凯强
	public function klass()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		$klasses = Klass::paginate(5);
		$page = $klasses->render();
        $this->assign('klasses', $klasses);
        $this->assign('page', $page);
		return $this->fetch();
	}

	// 管理员界面班级增加————赵凯强
	public function klassadd()
	{
		return $this->fetch();
	}

	// 管理员界面班级增加保存————赵凯强
	public function klasssave()
    {
    	$request = $this->request;
    	$Klass = new Klass();

    	$data = [
	    	'name' => $request->param('name'),
	    	'academy' => $request->param('academy'),
	    	'major' => $request->param('major'),
	    	'grade' => $request->param('grade'),
	    ];

	    $validate = new \app\index\validate\KlassValidate;
	    if (!$validate->check($data)) {
	    	return $this->error('数据添加错误：' . $Klass->getError());
	    } else {
	    	
	    	$Klass->name = $request->param('name');
	    	$Klass->academy = $request->param('academy');
	    	$Klass->major = $request->param('major');
	    	$Klass->grade = $request->param('grade');
	    	if(!$Klass->save()) {
	    		return $this->error('数据添加错误：' . $Klass->getError());
	    	} else {
	    		return $this->success('数据添加成功', url('klass'));
	    	}
	    }
    }
    
    // 管理员界面课程编辑跳转————赵凯强
    public function klassedit()
    {
    	$id = $this->request->param('id/d');
        // 获取用户操作的班级信息
        if (false === $Klass = Klass::get($id))
        {
        	return $this->error('未找到ID为' . $id . '的记录');
        }

        $this->assign('Klass', $Klass);
        return $this->fetch();
    }
    
    // 班级模块数据更新————赵凯强
    public function klassupdate()
    {

    	$id = $this->request->param('id/d');

    	// 获取传入的班级信息
    	$Klass = Klass::get($id);
    	if (is_null($Klass)) {
    		return $this->error('未找到ID为' . $id . '的记录');
    	}
        $request = $this->request;
    
	    	$Klass->name = $request->param('name');
	    	$Klass->academy = $request->param('academy');
	    	$Klass->major = $request->param('major');
	    	$Klass->grade = $request->param('grade');
	   

	    $validate = new \app\index\validate\KlassValidate;
	    if (!$validate->check($Klass)) {
	    	return $this->error('数据更新错误：' . $validate->getError());
	    } else {
	    	
	    	if(!$Klass->save()) {
	    		return $this->error('数据更新错误：' . $Klass->getError());
	    	} else {
	    		return $this->success('数据更新成功', url('klass'));
	    	}
	    }
    }

    // 班级模块数据删除————赵凯强
    public function klassdelete()
    {
    	$id = $this->request->param('id/d');

    	if(is_null($id) || 0 === $id) {
    		return $this->error('未获取到ID信息');

    	}

    	// 获取到要删除的对象
    	$Klass = Klass::get($id);
    	if (is_null($Klass)) {
    		return $this->error('不存在id为' . $id . '的班级');
    	}

    	// 删除对象
    	$students = Student::where('klass_id', $id)->select();
        
    	 if (count($students)>0) {
    	 	return $this->error('删除失败,班级中有学生' . $Klass->getError());
    	} else {
    		if (!$Klass->delete()) {
    			return $this->error('删除失败：' . $Klass->getError());
    		}
    	}

    	 //进行跳转
         return $this->success('删除成功', url('klass'));



    }

	// 管理员界面学生管理--李美娜
	public function student()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);

		$id = $this->request->param('id/d');
		if (is_null($id)) {
			return $this->fetch('klass');
		}


		$Student = new Student();
        $students = $Student->where('klass_id', $id)->paginate(5);
        

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
		$klasses = Klass::all();
		$this->assign('klasses', $klasses);
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
		$Student->password = $postData['password'];
		$Student->klass_id = $postData['klass_id'];
		
		// 添加数据
		if (!$Student->save()) {
			return $this->error('数据添加错误：' . $Student->getError());
		}

		return $this->success('操作成功', url('student?id=' . $Student->getData('klass_id')));
		
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
		if (!$Student->delete()) {
			return $this->error('删除成功:' . $Student->getError());
			}

		// 进行跳转
		return $this->success('删除成功', url('student?id=' . $Student->getData('klass_id')));
	}

	//管理员学生的编辑--李美娜
	public function studentedit()
	{

		$klasses = Klass::all();
		$this->assign('klasses', $klasses);

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
        $Student->name = $this->request->param('name');
        $Student->num = $this->request->param('num');
        $Student->sex = $this->request->param('sex/d');
        $Student->password = $this->request->param('password');
        $Student->klass_id = $this->request->param('klass_id');

        // 更新
        $message = '更新成功';
        if (false === $Student->save()) {
            $message =  '更新失败' . $Student->getError();
        }


        // 进行跳转
        
		return $this->success($message, url('student?id=' . $Student->getData('klass_id')));
	}

	//管理员模块教室管理——刘宇轩
	public function classroom()
	{
		// 获取当前方法名
        $this->assign('isaction',Request::action());

		// 获取当前学期状态
        $ifterm = Term::ifterm();
        $this->assign('ifterm', $ifterm);
        
		$classroom = new Classroom;
		$classrooms = $classroom->paginate(5);

		// $page = $classrooms->render();
        
        // 向v层传输数据
		$this->assign('classrooms',$classrooms);
		// $this->assign('page',$page);
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

	//管理员模块教室管理插入教室——刘宇轩--李美娜
	public function classroomsave()
	{
		// 接收传入的数据
		$postData = $this->request->post();

		// 实例化空classroom对象
		$classroom = new Classroom();

		// 为对象赋值
		$classroom->classroomplace = $postData['classroomplace'];
		$classroom->classroomname = $postData['classroomname'];
		$classroom->row = $postData['row'];
		$classroom->column = $postData['column'];

		// 添加数据
        if (!$classroom->save())       	
        {
            return $this->error('数据添加错误：' . $classroom->getError());
        }
        return $this->success('操作成功', url('classroom'));
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