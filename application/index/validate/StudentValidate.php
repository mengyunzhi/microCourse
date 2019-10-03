<?php
namespace app\index\validate;
use think\Validate;    //内置验证类

class StudentValidate extends Validate
{
	protected $rule = [

    'id' => 'require|length:1,25',
		'name' => 'require|length:2,25',
		'num' => 'alphaNum|length:3,30|unique:student',
		'sex' => 'in:0,1',
        'password' => 'alphaDash|require|length:3,20',
		'klass_id' => 'require|number',
    ];

    protected $scene = [
        'update'  =>  ['password'],
    ];


    // public static function getUpdateValidate() {
    // 	return [
    // 		'password' => 'require|length:3,40',
	   //  ];
    // }
}
