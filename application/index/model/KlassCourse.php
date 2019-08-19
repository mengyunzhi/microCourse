<?php

namespace app\index\model;
use think\Model;
use app\index\validate\KlassCourseValidate;

class KlassCourse extends Model
{
	// private static $validate;

	// public function save($data = [], $where = [], $sequence = null)
 //    {
 //    	if (!$this->validate($this)) {
 //    		return false;
 //    	}

 //    	return parent::save($data, $where, $sequence);
 //    }

 //    private function validate() {
 //    	if (is_null(self::$validate)) {
 //    		self::$validate = new KlassCourseValidate();
 //    	}

 //    	return self::$validate->check($this);
 //    }
}