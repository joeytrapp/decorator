<?php

require(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "DecoratorFactory.php");
require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class SampleDecorator extends Decorator {
}

class DecoratorFactoryTest extends CakeTestCase {

	public $data = array("Sample" => array("id" => 5, "content" => "Sample string"));

	public function testCreateMethodReturnsDecoratorOrDecoratorInstance() {
		$d = DecoratorFactory::create("Custom");
		$this->assertEquals(get_class($d), "Decorator");
	}
	
	public function testBuildMethodReturnsArrayWhenNoDataPassed() {
		$d = DecoratorFactory::build("ModelName", array());
		$this->assertEmpty($d);
		$d = DecoratorFactory::build("ModelName");
		$this->assertEmpty($d);
		$d = DecoratorFactory::build("ModelName", null);
		$this->assertEmpty($d);
		$d = DecoratorFactory::build("ModelName", false);
		$this->assertEmpty($d);
	}

	public function testCreateRealDecorator() {
		$d = DecoratorFactory::create("Sample", $this->data);
		$this->assertEquals(get_class($d), "SampleDecorator");
		$this->assertEquals($d->id(), 5);
		$this->assertEquals($d->content(), "Sample string");
	}

	public function testBuildCollectionOfDecorators() {
		$data = array($this->data, $this->data);
		$d = DecoratorFactory::build("Sample", $data);
		$this->assertEquals(count($d), 2);
		$this->assertEquals(get_class($d[0]), "SampleDecorator");
		$this->assertEquals($d[0]->id(), 5);
		$this->assertEquals($d[0]->content(), "Sample string");
	}

	public function testCreateCanMakeDecoratorsWithoutParsing() {
		$d = DecoratorFactory::create("Test", $this->data, false);
		$this->assertEquals($d->raw("Sample"), $this->data["Sample"]);
	}

	public function testBuildCanMakeDecoratorsWithoutParsing() {
		$data = array($this->data, $this->data);
		$d = DecoratorFactory::build("Test", $data, false);
		$this->assertEquals($d[0]->raw("Sample"), $this->data["Sample"]);
		$this->assertEquals($d[1]->raw("Sample"), $this->data["Sample"]);
	}

}

