<?php
/**
 * DecoratorFactory 
 * 
 * @package View.Decorator
 * @version $id$
 * @author Joey Trapp <jtrapp07@gmail.com> 
 */

require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorFactory {

	/**
	 * create 
	 * 
	 * @param mixed $name 
	 * @param array $data 
	 * @param mixed $parse
	 * @static
	 * @access public
	 * @return void
	 */
	public static function create($name, $data = array(), $parse = true) {
		$class = $name . "Decorator";
		$path = APP . "View" . DS . "Decorator" . DS;
		if (class_exists($class)) {
			return new $class($data, $parse);
		} else if (file_exists($path . $class . ".php")) {
			include($path . $class . ".php");
			return new $class($data, $parse);
		}
		if (class_exists("AppDecorator")) {
			return new AppDecorator($data, $parse);
		} else if (file_exists($path . "AppDecorator.php")) {
			include($path . "AppDecorator.php");
			return new AppDecorator($data, $parse);
		} else {
			return new Decorator($data, $parse);
		}
	}

	/**
	 * build 
	 * 
	 * @param mixed $name 
	 * @param array $data 
	 * @param mixed $parse
	 * @static
	 * @access public
	 * @return void
	 */
	public static function build($name, $data = array(), $parse = true) {
		if (!is_array($data)) { return array(); }
		$instances = array();
		foreach ($data as $key => $model) {
			$instances[$key] = self::create($name, $model, $parse);
		}
		return $instances;
	}
	
}

