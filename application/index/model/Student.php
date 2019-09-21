<?php
namespace app\index\model;
use think\Model;    //  导入think\Model类
use app\index\validate\StudentValidate; 
/**
 * @Author: limeina1
 * @Date:   2019-08-13 10:47:26
 */
/**
 * 学生
 */

class Student extends Model
{
	// private static $validate;

	// public function save($data = [], $where = [], $sequence = null)
	// {
	// 	if (!$this->validate($this)) {
	// 		return false;
	// 	}

	// 	return parent::save($data, $where, $sequence);
	// }

	// private function validate() {
	// 	if (is_null(self::$validate)) {
	// 		self::$validate = new StudentValidate();
	// 	}
	//	return self::$validate->check($this);
	//}
    // 学生找班级————赵凯强
	public function klass()
	{
		return $this->belongsTo('klass');
	}   

	// 性别转换————赵凯强
	// public function getSexAttr($value)
	// {
	// 	$status = [1=>'女',0=>'男'];
	// 	if ($value==null) {
 //           return '未设定';
	// 	}
	// 	return $status[$value];
	// }

    // 学生得到班级名字————赵凯强
	public function getKlass()
	{
		
		$KlassId = $this->getData('klass_id');
		$Klass = Klass::get($KlassId); 
		return $Klass;
	}
    
    // 学生登录验证————赵凯强
	static public function login($num, $password)
	{
		// 验证用户是否存在
		$map = array('num' => $num);
		$Student = self::get($map);

		if (!is_null($Student)) {
			// 验证密码是否正确
			if ($Student->checkPassword($password)) {
				// 登录
				session('studentId', $Student->getData('id'));
				return true;
			}
		}
		return false;
	}

	// 学生通过微信登录————刘宇轩
	static public function Wxlogin($openid)
	{
		// 验证用户是否存在
		$map = array('openid' => $openid);
		$Student = self::get($map);

		if (!is_null($Student)) {
			session('studentId', $Student->getData('id'));
			return true;
		}
		return false;
	}
    
	// 学生通过微信登录(传ID)————刘宇轩
	static public function WxloginID($openid)
	{
		// 验证用户是否存在
		$map = array('id' => $openid);
		$Student = self::get($map);

		if (!is_null($Student)) {
			session('studentId', $Student->getData('id'));
			return true;
		}
		return false;
	}

    // 学生登录密码验证————赵凯强
	public function checkPassword($password)
	{
		if ($this->getData('password') === $password)
		{
			return true;
		} else {
			return false;
		}
	}
    
    // 学生注销————赵凯强
	static public function logOut()
	{
		// 销毁session中数据
		session('studentId', null);
		return true;
	} 

    // 学生操作验证————赵凯强
	static public function isLogin()
	{
		$studentId = session('studentId');

		// isset()和is_null是一对反义词
		if (isset($studentId)) {
			return true;
		} else {
			return false;
		}
	}


}