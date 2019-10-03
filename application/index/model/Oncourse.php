<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-17 15:27:10
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-17 17:09:11
 */
namespace app\index\model;
use think\Model;

class oncourse extends Model
{
	public function student()
	{
		return $this->belongsTo('student');
	}
}
