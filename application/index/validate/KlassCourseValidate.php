<?php
namespace app\index\validate;
use think\Validate;

class KlassCourseValidate extends Validate
{
	protected $rule = [
		'klass_id' => 'number',
		'course_id' => 'number',
	];
}