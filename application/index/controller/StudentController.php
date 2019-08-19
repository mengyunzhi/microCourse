<?php
namespace app\index\controller;
use app\common\model\Index;
use think\Controller;
/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-14 16:56:21
 */
class StudentController extends IndexController
{
	
	public function studentpage()
	{

		return $this->fetch();
	}

	public function studentonline()
	{
		return $this->fetch();
	}

	public function studentcourse()
	{
		return $this->fetch();
	}

	public function studentcoursetime()
	{
		return $this->fetch();
	}

	public function studentoncourse()
	{
		return $this->fetch();
	}

	public function studentseat()
	{
		return $this->fetch();
	}

	public function studentscore()
	{
		return $this->fetch();
	}

	public function studentinfo()
	{
		return $this->fetch();
	}	
    
    public function studentinfoedit()
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