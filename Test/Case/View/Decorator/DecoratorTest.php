<?php

require(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class TestDecorator extends Decorator {
}

class CustomDecorator extends Decorator {
	public $name = "Something";
}

class ComplexDecorator extends Decorator {
	public $name = "Test";
	public function id() {
		$id = $this->raw("id");
		return "<p>{$id}</p>";
	}
	public function content() {
		$content = $this->raw("content");
		return "<p>{$content}</p>";
	}
}

class DecoratorTest extends CakeTestCase {

	public $data = array("Test" => array("id" => 5, "content" => "Some string"));

	public function testNewDecoratorBuildsModelName() {
		$d = new TestDecorator();
		$this->assertEquals($d->name, "Test");
	}

	public function testDecoratorCanDefineDifferentModelName() {
		$d = new CustomDecorator();
		$this->assertEquals($d->name, "Something");
	}

	public function testDecoratorCanImportDataForItsModelName() {
		$d = new TestDecorator($this->data);
		$this->assertEquals($d->model, $this->data["Test"]);
	}

	public function testDecoratorWillAssignAllDataIfNoMatch() {
		$d = new CustomDecorator($this->data);
		$this->assertEquals($d->model, $this->data);
	}

	public function testDecoratorWillReturnDataFromUndefinedMethods() {
		$d = new TestDecorator($this->data);
		$this->assertEquals($d->id(), $this->data["Test"]["id"]);
		$this->assertEquals($d->content(), $this->data["Test"]["content"]);
	}

	public function testDecoratorCanDefineMethodsForDataKeys() {
		$d = new ComplexDecorator($this->data);
		$this->assertTags($d->id(), array('p' => array(), 5, '/p'));
		$this->assertTags($d->content(), array('p' => array(), "Some string", '/p'));
	}

}
