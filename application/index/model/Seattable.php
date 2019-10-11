<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-10-10 20:31:47
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-10 20:47:12
 */


namespace app\index\model;
use think\Model;

class Seattable extends Model
{


    public function student()
    {
        return $this->belongsTo('student');
    }  

    public function classroom_time()
    {
        return $this->belongsTo('classroom_time');
    }  

}