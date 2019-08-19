<?php
namespace app\index\controller;

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 09:45:27
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

	public function course()
	{
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