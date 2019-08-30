<?php
namespace app\index\validate;
use think\Validate;

class KlassValidate extends Validate
{
	protected $rule = [
        'name' => 'require|length:2,100',
        'academy' => 'require|length:2,100',
        'major' => 'require|length:2,100',
        'grade' => 'require|length:2,100',
	];
}