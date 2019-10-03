<?php
namespace app\index\validate;
use think\Validate;    //内置验证类

class TermValidate extends Validate
{
	protected $rule = [
		'name' => 'require|unique:term',
		'start' => 'date',
		'end' => 'date',
		'length' => 'require|number',
		'state' => 'in:0,1',		
    ];
}
