<?php

require_once(App::pluginPath("Decorator") . "View" . DS . "Decorator" . DS . "Decorator.php");

class DecoratorTestDecorator extends Decorator {
}

class DecoratorTestCustomDecorator extends Decorator {
	public $name = "Something";
}

class DecoratorTestComplexDecorator extends Decorator {
	public $name = "DecoratorTest";
	public function id() {
		$id = $this->_raw("id");
		return "<p>{$id}</p>";
	}
	public function content() {
		$content = $this->_raw("content");
		return "<p>{$content}</p>";
	}
}

class DecoratorTest extends CakeTestCase {

	public $data = array("DecoratorTest" => array("id" => 5, "content" => "Some string"));

	public function testNewDecoratorBuildsModelName() {
		$d = new DecoratorTestDecorator();
		$this->assertEquals("DecoratorTest", $d->name);
	}

	public function testDecoratorCanDefineDifferentModelName() {
		$d = new DecoratorTestCustomDecorator();
		$this->assertEquals("Something", $d->name);
	}

	public function testDecoratorCanImportDataForItsModelName() {
		$d = new DecoratorTestDecorator($this->data);
		$this->assertEquals($this->data["DecoratorTest"], $d->model);
	}

	public function testDecoratorWillAssignAllDataIfNoMatch() {
		$d = new DecoratorTestCustomDecorator($this->data);
		$this->assertEquals($this->data, $d->model);
	}

	public function testDecoratorWillReturnDataFromUndefinedMethods() {
		$d = new DecoratorTestDecorator($this->data);
		$this->assertEquals($this->data["DecoratorTest"]["id"], $d->id());
		$this->assertEquals($this->data["DecoratorTest"]["content"], $d->content());
	}

	public function testDecoratorCanDefineMethodsForDataKeys() {
		$d = new DecoratorTestComplexDecorator($this->data);
		$this->assertTags($d->id(), array('p' => array(), 5, '/p'));
		$this->assertTags($d->content(), array('p' => array(), "Some string", '/p'));
	}

	public function testMethodCallToNonExistingKeyWillException() {
		$d = new DecoratorTestDecorator($this->data);
		try {
			$d->missing();
		} catch (Exception $e) {
			$this->assertEquals("No method missing() defined", $e->getMessage());
			return;
		}
		$this->fail("An exception should have been raised");
	}
	
	public function testRawCallWithInvalidKeyRaisesException() {
		$d = new DecoratorTestDecorator($this->data);
		try {
			$d->raw("missing");
		} catch (Exception $e) {
			$this->assertEquals("Undefined index: missing", $e->getMessage());
			return;
		}
		$this->fail("An exception should have been raised");
	}

	public function testCanCreateDecoratorWithoutParsing() {
		$d = new DecoratorTestCustomDecorator($this->data, false);
		$this->assertEquals($this->data["DecoratorTest"], $d->raw("DecoratorTest"));
	}

}

