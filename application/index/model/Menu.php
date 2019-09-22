<?php

namespace app\index\model;

use think\facade\Request;

class Menu {
	public $action;

	public $name;

	public function __construct($action, $name) {
		$this->action = $action;
		$this->name = $name;
	}

	public function isActive() {
		return Request::action() === $this->action;
	}

	public function getUrl() {
		return url('index/admin/' . $this->action);
	}

	public function getClass() {
		return $this->isActive() ? 'active' : '';
	}
}