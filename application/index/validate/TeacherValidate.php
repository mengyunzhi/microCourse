<?php
namespace app\index\validate;
use think\Validate;   // 内置验证类
 
 class TeacherValidate extends Validate
 {
 	protected $rule = [
 		'name' => 'require|length:2,25',
 		'num' => 'alphaNum|length：3,30|unique:teacher',
 		'sex' => 'in:0,1',
 		'password' => 'alphaDash|require|length:3,30',
 	];
 }