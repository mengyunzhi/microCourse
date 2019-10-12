<?php

namespace app\index\model;
use think\Model;

class Classroom_time extends Model
{

    public function classroom()
    {
        return $this->belongsTo('classroom');
    }  

    public function courseinfo()
    {
        return $this->belongsTo('courseinfo');
    }  
}