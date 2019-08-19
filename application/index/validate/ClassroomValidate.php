<?php
namespace app\index\validate;
use think\Validate;   // 内置验证类
 
class ClassroomValidate extends Validate
{
 	protected $rule = [
 		'classroomplace' => 'require|length:2,25',
 		'classroomname' => 'require|length:2,25',
 		'row' => 'require|number',
 		'column' => 'require|number',
 	];
}