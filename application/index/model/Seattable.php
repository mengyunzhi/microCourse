<?php


namespace app\index\model;
use think\Model;

class Seattable extends Model
{

    public function student()
    {
        return $this->belongsTo('student');
    }  
}