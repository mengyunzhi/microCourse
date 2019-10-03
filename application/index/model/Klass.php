<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-16 15:44:22
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-17 10:14:39
 */
namespace app\index\model;
use think\Model;

class Klass extends Model
{
	public function course()
    {
        return $this->belongsToMany('course');
    }

    public function college()
	{
		return $this->belongsTo('college');
	}   
} 

