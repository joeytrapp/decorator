<?php

require(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorFactory {

	public static function create($name, $data = array()) {
		$class = $name . "Decorator";
		$path = APP . "View" . DS . "Decorator";
		if (class_exists($class)) {
			return new $class($data);
		} else if (file_exists($path . $class . ".php")) {
			include($path . $class . ".php");
			return new $class($data);
		}
		if (class_exists("AppDecorator")) {
			return new AppDecorator($data);
		} else if (file_exists($path . "AppDecorator.php")) {
			include($path . "AppDecorator.php");
			return new AppDecorator($data);
		} else {
			return new Decorator($data);
		}
	}

	public static function build($name, $data = array()) {
		if (!is_array($data)) { return array(); }
		$instances = array();
		foreach ($data as $key => $model) {
			$instances[$key] = self::create($name, $model);
		}
		return $instances;
	}
	
}

