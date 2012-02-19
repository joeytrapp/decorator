<?php

App::uses("Controller", "Controller");
App::uses("DecoratorComponent", "Decorator.Controller/Component");
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorComponentTestDecorator extends Decorator {
}

class DecoratorComponentTestController extends Controller {
	public $helpers = array();
}

class DecoratorComponentTest extends CakeTestCase {

	public $Controller;

	public $Decorator;

	public $data = array("DecoratorComponentTest" => array(
		"id" => 5,
		"content" => "Some content"
	));

	public function setUp() {
		parent::setUp();
		$request = new CakeRequest("controller_post/index");
		$response = new CakeResponse();
		$this->Controller = new DecoratorComponentTestController($request, $response);
		$this->Controller->constructClasses();
		$this->Decorator = new DecoratorComponent($this->Controller->Components);
	}

	public function tearDown() {
		parent::tearDown();
		unset($this->Controller, $this->Decorator);
	}

	public function testHelperIsAddedToControllerDuringStartup() {
		$this->assertEmpty($this->Controller->helpers);
		$this->Decorator->startup($this->Controller);
		$this->assertContains("Decorator.Decorator", $this->Controller->helpers);
	}

	public function testCreateReturnsBaseDecoratorIfClassNotFound() {
		$d = $this->Decorator->create("NoExist", array());
		$this->assertEquals("Decorator", get_class($d));
	}

	public function testCreateReturnsDecoratorClassIfExists() {
		$d = $this->Decorator->create("DecoratorComponentTest", $this->data);
		$this->assertEquals("DecoratorComponentTestDecorator", get_class($d));
		$this->assertEquals(5, $d->id());
	}

	public function testBuildReturnsListOfTheSameSizeFromInvalidName() {
		$data = array($this->data, $this->data);
		$d = $this->Decorator->build("NoExist", $data);
		$this->assertEquals(2, count($d));
		$this->assertEquals("Decorator", get_class($d[0]));
		$this->assertEmpty($d[0]->model);
		$this->assertEquals(5, $d[0]->DecoratorComponentTest->id());
	}

	public function testBuildReturnsListOfTheSameSizeValidName() {
		$data = array($this->data, $this->data, $this->data);
		$d = $this->Decorator->build("DecoratorComponentTest", $data);
		$this->assertEquals(3, count($d));
		$this->assertEquals("DecoratorComponentTestDecorator", get_class($d[0]));
		$this->assertEquals("Some content", $d[2]->content());
	}
	
}
