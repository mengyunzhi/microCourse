<?php
namespace app\index\validate;
use think\Validate;

class KlassValidate extends Validate
{
	protected $rule = [
        'name' => 'require|unique:klass',
        'academy' => 'number',
        'major' => 'require|length:2,20',
        'grade' => 'require|length:2,20',
	];

	protected $scene = [
        'update'  =>  ['name'],
    ];

   
}