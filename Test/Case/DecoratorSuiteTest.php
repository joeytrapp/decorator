<?php

class DecoratorSuiteTest extends CakeTestSuite {

	public static function suite() {
		$path = App::pluginPath("Decorator") . "Test" . DS . "Case" . DS;
		$suite = new CakeTestSuite("Decorator Plugin Suite");
		$suite->addTestDirectoryRecursive($path . "View");
		$suite->addTestDirectory($path . "Controller" . DS . "Component");
		return $suite;
	}

}

