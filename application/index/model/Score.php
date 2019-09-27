<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-17 20:59:17
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-09-27 20:04:06
 */
namespace app\index\model;
use think\Model;

class score extends Model
{
	// 通过score表调用course表——赵凯强
	public function Course()
	{
		return $this->belongsTo('course');
	}

	public function Student()
	{
		return $this->belongsTo('student');
	}

}
