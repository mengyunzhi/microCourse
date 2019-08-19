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

	// 	return self::$validate->check($this);
	// }
}