<?php
namespace app\index\validate;
use think\Validate;    //内置验证类

class StudentValidate extends Validate
{
	protected $rule = [
		'name' => 'require|length:2,25',
		'num' => 'require|unique:student',
		'sex' => 'in:0,1',
		'academy' => 'require',
		'major' => 'require',
		'klass_id' => 'require|number',
        ];
}
