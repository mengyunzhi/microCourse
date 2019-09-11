<?php

class StudentEditValidate extend StudentValidate {
	protected $rule = [
		Student::$password: $this->rules[Student::$password]
	];
}