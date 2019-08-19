<?php
namespace app\index\validate;
use think\Validate;   // 内置验证类
 
 class CourseValidate extends Validate
 {
 	protected $rule = [
 		'name' => 'require|length:2,100',
 		'num' => 'require|unique:course',
 		'term_id' => 'require|length:1,25',
 		'teacher_id' => 'require|length:1,25',
 		'type' => 'in:0,1',
 	];
 }