<?php
namespace app\index\model;
use think\Model;

class College extends Model
{
    public function Klasses()
	{
		return $this->hasMany('Klass');
	}
}