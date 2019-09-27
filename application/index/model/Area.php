<?php
namespace app\index\model;
use think\Model;

class Area extends Model
{
    public function Classroom()
	{
		return $this->hasMany('Classroom');
	}
}