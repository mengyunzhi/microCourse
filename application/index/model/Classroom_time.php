<?php

namespace app\index\model;
use think\Model;

class classroom_time extends Model
{

    public function classroom()
    {
        return $this->belongsTo('classroom');
    }  
}