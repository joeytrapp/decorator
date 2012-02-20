<?php

App::uses("DecoratorHelper", "Decorator.View/Helper");
App::uses("Controller", "Controller");
require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorHelperTestDecorator extends Decorator {
}

class DecoratorHelperTestController extends Controller {
}

class DecoratorHelperTest extends CakeTestCase {

	public $Decorator;

	public $View;

	public $data = array("DecoratorHelperTest" => array(
		"id" => 5,
		"content" => "Some content"
	));

	public function setUp() {
		parent::setUp();
		$this->View = $this->getMock('View', array('addScript'), array(new DecoratorHelperTestController()));
		$this->Decorator = new DecoratorHelper($this->View);
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testCreateReturnsBaseDecoratorIfClassNotFound() {
		$d = $this->Decorator->create("NoExist", array());
		$this->assertEquals("Decorator", get_class($d));
	}

	public function testCreateReturnsDecoratorClassIfExists() {
		$d = $this->Decorator->create("DecoratorHelperTest", $this->data);
		$this->assertEquals("DecoratorHelperTestDecorator", get_class($d));
		$this->assertEquals(5, $d->id());
	}

	public function testBuildReturnsListOfTheSameSizeFromInvalidName() {
		$data = array($this->data, $this->data);
		$d = $this->Decorator->build("NoExist", $data);
		$this->assertEquals(2, count($d));
		$this->assertEquals("Decorator", get_class($d[0]));
		$this->assertEmpty($d[0]->model);
		$this->assertEquals(5, $d[0]->DecoratorHelperTest->id());
	}

	public function testBuildReturnsListOfTheSameSizeValidName() {
		$data = array($this->data, $this->data, $this->data);
		$d = $this->Decorator->build("DecoratorHelperTest", $data);
		$this->assertEquals(3, count($d));
		$this->assertEquals("DecoratorHelperTestDecorator", get_class($d[0]));
		$this->assertEquals("Some content", $d[2]->content());
	}
	
}
