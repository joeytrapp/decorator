<?php

require(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "DecoratorFactory.php");

class DecoratorFactoryTest extends CakeTestCase {

	public function testCreateMethodReturnsDecoratorOrDecoratorInstance() {
		$d = DecoratorFactory::create("Custom");
		$this->assertEquals(get_class($d), "Decorator");
	}
	
	public function testBuildMethodReturnsArrayWhenNoDataPassed() {
		$d = DecoratorFactory::build("ModelName", array());
		$this->assertEquals($d, array());
		$d = DecoratorFactory::build("ModelName");
		$this->assertEquals($d, array());
		$d = DecoratorFactory::build("ModelName", null);
		$this->assertEquals($d, array());
		$d = DecoratorFactory::build("ModelName", false);
		$this->assertEquals($d, array());
	}

}

