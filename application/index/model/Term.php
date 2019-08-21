<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 10:09:10
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 11:01:05
 */
namespace app\index\model;
use think\Model;
use app\index\validate\TermValidate;

class Term extends Model
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
    		self::$validate = new TermValidate();
    	}

    	return self::$validate->check($this);
    }

    // trem一对多查询course函数——赵凯强
    public function Course()
    {
        return $this->hasMany('Course');
    }
}