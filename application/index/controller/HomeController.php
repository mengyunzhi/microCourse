<?php
namespace app\index\controller;
/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 15:52:17
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-08 19:06:08
 */
class HomeController extends IndexController
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

	public function studentincourse()
	{
		return $this->fetch();
	}

	public function studentscore()
	{
		return $this->fetch();
	}

	public function adminpage()
	{
		return $this->fetch();
	}

	public function adminterm()
	{
		return $this->fetch();
	}

	public function teacherpage()
	{
		return $this->fetch();
	}	

	public function studentinfo()
	{
		return $this->fetch();
	}	
    
    public function teachercourse()
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

    public function teachergrade()
    {
    	return $this->fetch();
    }
}