<?php

App::uses("Object", "Core");

class Decorator extends Object {

	public $modelName = false;
	
	public $model = null;

	public function __construct($data = array()) {
	}

	public function __call($method, $params = array()) {
	}

}

