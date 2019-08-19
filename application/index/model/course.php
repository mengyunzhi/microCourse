<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-14 20:14:43
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-16 17:34:30
 */
namespace app\index\model;
use think\Model;

class course extends Model
{
	public function Term()
	{
		return $this->belongsTo('Term');
	}

	public function teacher()
	{
		return $this->belongsTo('teacher');
	}

	public function courseinfo()
    {
        return $this->hasMany('courseinfo');
    }

    public function klass()
    {
    	return $this->belongsToMany('klass',config('database.prefix') . 'klass_course');
    }

}