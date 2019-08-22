<?php
namespace app\index\controller;
use app\index\model\Index;
use think\Controller;
use app\index\model\Term;

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 09:42:52
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-22 15:09:20
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
    	// $day = Term::largeClass();
    	// return $day;
    	// $day1 = "2019-08-10";
		// $day2 = date("Y-m-d");
		// echo $day2;
		// echo "空格";
		// $diff = index::weekday($day2);
		// echo $diff;
      	// return $this->fetch();
    }
}