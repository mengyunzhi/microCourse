<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 21:33:11
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 21:33:41
 */
namespace app\index\model;
use think\Model;
use app\index\validate\ClassroomValidate;

class classroom extends Model
{
	private static $validate;

    public function save($data = [], $where = [], $sequence = null)
    {
    	if (!$this->validate($this)) {
    		return false;
    	}

    	return parent::save($data, $where, $sequence);
    }

    private function validate() {
    	if (is_null(self::$validate)) {
    		self::$validate = new ClassroomValidate();
    	}

    	return self::$validate->check($this);
    }
}