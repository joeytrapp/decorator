<?php
/**
 * Decorator 
 * 
 * @package View.Decorator
 * @version $id$
 * @author Joey Trapp <jtrapp07@gmail.com> 
 */

require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "DecoratorFactory.php");

class Decorator {

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
	 * @param mixed $parse
	 * @access public
	 * @return void
	 */
	public function __construct($data = array(), $parse = true) {
		if (!$this->name) {
			$this->name = preg_replace("/Decorator$/", "", get_class($this));
		}
		$this->model = $this->parseData($data, $parse);
	}

	/**
	 * Takes the data array that is passed into the construct method parses out
	 * modelName key if it exists. Overwrite this method to add custom data
	 * parsing logic. 
	 * 
	 * @param array $data 
	 * @param mixed $parse
	 * @access public
	 * @return array
	 */
	public function parseData($data = array(), $parse = true) {
		$ret = array();
		$name = $this->name;
		if (is_string($parse)) {
			$name = $parse;
		}
		if ($parse) {
			if (array_key_exists($name, $data)) {
				$ret = $data[$name];
				unset($data[$name]);
				// Builds sibling associations
				$this->_buildAssociations($data);
				// Builds nested associations
				$this->_buildAssociations($ret);
			} else {
				// Builds nested associations and returns array of non model key/values
				$data = $this->_buildAssociations($data);
				$ret = $data;
			}
		} else {
			$ret = $data;
		}
		return $ret;
	}

	/**
	 * Takes an array of data loops over them. If any of the keys match a model
	 * name regex, then the value for that key is inspected. If the value is an
	 * array and they keys are numeric, then the DecoratorFactory::build() method
	 * is called. If the value is array and the keys are not numeric then the
	 * DecoratorFactory::create() method is called. The results of build() or
	 * create() are assigned to properties of the model name on this decorator.
	 * All keys that are used to create decorators are unset from the data and
	 * the data array is returned.
	 * 
	 * @param mixed $data 
	 * @access protected
	 * @return array
	 */
	protected function _buildAssociations($data) {
		$keys = array_keys($data);
		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $data[$key];
			if (
				preg_match("/^[A-Z]{1}[a-zA-Z]+/", $key) &&
				is_array($value) &&
				!empty($value)
			) {
				if (preg_match("/[0-9]+/", key($value))) {
					$this->{$key} = DecoratorFactory::build($key, $value, $key);
				} else {
					$this->{$key} = DecoratorFactory::create($key, $value, $key);
				}
				unset($data[$key]);
			}
		}
		return $data;
	}

	/**
	 * By default this is available to return the raw value from the model
	 * array. The logic is in the _raw protected method so that the raw
	 * method can be overwritten if you have a key called raw. 
	 * 
	 * @param mixed $key 
	 * @access public
	 * @return void
	 */
	public function raw($key) {
		return $this->_raw($key);
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
	protected function _raw($key = null) {
		if ($key && array_key_exists($key, $this->model)) {
			return $this->model[$key];
		}
		throw new Exception("Undefined index: {$key}");
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
		throw new Exception("No method {$method}() defined");
	}

}

