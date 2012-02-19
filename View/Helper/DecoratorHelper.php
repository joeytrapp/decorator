<?php
/**
 * DecoratorHelper 
 * 
 * @uses AppHelper
 * @package View.Helper
 * @version $id$
 * @author Joey Trapp <jtrapp07@gmail.com> 
 */

require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "DecoratorFactory.php");

class DecoratorHelper extends AppHelper {

	/**
	 * create 
	 * 
	 * @param mixed $name 
	 * @param array $data 
	 * @access public
	 * @return void
	 */
	public function create($name, $data = array(), $parse = true) {
		return DecoratorFactory::create($name, $data, $parse);
	}

	/**
	 * build 
	 * 
	 * @param mixed $name 
	 * @param array $data 
	 * @access public
	 * @return void
	 */
	public function build($name, $data = array(), $parse = true) {
		return DecoratorFactory::build($name, $data, $parse);
	}

}

