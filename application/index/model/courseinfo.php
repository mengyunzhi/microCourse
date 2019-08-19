<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-14 21:26:00
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-17 16:13:43
 */
namespace app\index\model;
use think\Model;

class courseinfo extends Model
{
	public function classroom()
	{
		return $this->belongsTo('classroom');
	}

	public function teacher()
	{
		return $this->belongsTo('teacher');
	}

	public function course()
	{
		return $this->belongsTo('course');
	}

}