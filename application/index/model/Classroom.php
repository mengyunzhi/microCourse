<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 21:33:11
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-19 17:19:59
 */
namespace app\index\model;
use think\Model;
use app\index\validate\ClassroomValidate;

class classroom extends Model
{

    public function area()
    {
        return $this->belongsTo('area');
    }  
}

