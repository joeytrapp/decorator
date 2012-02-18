<?php
/**
 * DecoratorHelper 
 * 
 * @uses AppHelper
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <toby@php.net> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
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

