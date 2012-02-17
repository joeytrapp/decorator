<?php
/**
 * Decorator 
 * 
 * @uses Object
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <toby@php.net> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */

App::uses("Object", "Core");

class Decorator extends Object {

	/**
	 * name 
	 * 
	 * @var mixed
	 * @access public
	 */
	public $name = false;
	
	/**
	 * model 
	 * 
	 * @var mixed
	 * @access public
	 */
	public $model = null;

	/**
	 * Constructor method that tries to figure out the modelName is one isn't set,
	 * then calls the parseData method to parse the passed in array of data.
	 * 
	 * @param array $data 
	 * @access public
	 * @return void
	 */
	public function __construct($data = array()) {
		if (!$this->name) {
			$this->name = str_replace("Decorator", "", get_class($this));
		}
		$this->model = $this->parseData($data);
	}

	/**
	 * Takes the data array that is passed into the construct method parses out
	 * modelName key if it exists. Overwrite this method to add custom data
	 * parsing logic. 
	 * 
	 * @param array $data 
	 * @access public
	 * @return array
	 */
	public function parseData($data = array()) {
		$ret = array();
		if (array_key_exists($this->name, $data)) {
			$ret = $data[$this->name];
		} else {
			$ret = $data;
		}
		return $ret;
	}

	/**
	 * Tries to return the key from the $this->model array. Not checking if the
	 * key exists first so that the undefined index exception will get thrown.
	 * May convert and throw my on exception here. 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function raw($key) {
		return $this->model[$key];
	}

	/**
	 * __call 
	 * 
	 * @param string $method 
	 * @param array $params 
	 * @access public
	 * @return mixed
	 */
	public function __call($method, $params = array()) {
		if (array_key_exists($method, $this->model)) {
			return $this->model[$method];
		}
		throw new Exception(get_class($this) . " can not handle {$method}()");
	}

}

