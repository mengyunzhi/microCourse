<?php
namespace app\index\validate;
use think\Validate;

class KlassValidate extends Validate
{
	protected $rule = [
        'name' => 'require|unique:klass',
        'academy' => 'require|length:2,20',
        'major' => 'require|length:2,20',
        'grade' => 'require|length:2,20',
	];
}