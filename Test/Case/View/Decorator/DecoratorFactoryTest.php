<?php

require(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "DecoratorFactory.php");
require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorFactoryTestSampleDecorator extends Decorator {
}

class DecoratorFactoryTest extends CakeTestCase {

	public $data = array("DecoratorFactoryTestSample" => array("id" => 5, "content" => "Sample string"));

	public function testCreateMethodReturnsDecoratorOrDecoratorInstance() {
		$d = DecoratorFactory::create("NoExist");
		$this->assertEquals("Decorator", get_class($d));
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
		$d = DecoratorFactory::create("DecoratorFactoryTestSample", $this->data);
		$this->assertEquals("DecoratorFactoryTestSampleDecorator", get_class($d));
		$this->assertEquals(5, $d->id());
		$this->assertEquals("Sample string", $d->content());
	}

	public function testBuildCollectionOfDecorators() {
		$data = array($this->data, $this->data);
		$d = DecoratorFactory::build("DecoratorFactoryTestSample", $data);
		$this->assertEquals(2, count($d));
		$this->assertEquals("DecoratorFactoryTestSampleDecorator", get_class($d[0]));
		$this->assertEquals(5, $d[0]->id());
		$this->assertEquals("Sample string", $d[0]->content());
	}

	public function testCreateCanMakeDecoratorsWithoutParsing() {
		$d = DecoratorFactory::create("Test", $this->data, false);
		$this->assertEquals($this->data["DecoratorFactoryTestSample"], $d->raw("DecoratorFactoryTestSample"));
	}

	public function testBuildCanMakeDecoratorsWithoutParsing() {
		$data = array($this->data, $this->data);
		$d = DecoratorFactory::build("Test", $data, false);
		$this->assertEquals($this->data["DecoratorFactoryTestSample"], $d[0]->raw("DecoratorFactoryTestSample"));
		$this->assertEquals($this->data["DecoratorFactoryTestSample"], $d[1]->raw("DecoratorFactoryTestSample"));
	}

}

