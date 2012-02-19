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

	public function testDecoratorWillNotAssignAllDataIfNoMatch() {
		$d = new DecoratorTestCustomDecorator($this->data);
		$this->assertEmpty($d->model);
	}

	public function testDecoratorCanTakeASecondArgToDefineTheParseName() {
		$d = new DecoratorTestCustomDecorator($this->data, "DecoratorTest");
		$this->assertEquals("DecoratorTestCustomDecorator", get_class($d));
		$this->assertEquals(5, $d->id());
	}

	public function testDecoratorWillAutoCreateSiblingObjects() {
		$data = array(
			"TestOne" => array("id" => 1, "content" => "Sample content"),
			"TestTwo" => array("id" => 2, "content" => "Sample content")
		);
		$d = new Decorator($data, "TestOne");
		$this->assertEquals(1, $d->id());
		$this->assertEquals(2, $d->TestTwo->id());
		$d = new Decorator($data, "TestTwo");
		$this->assertEquals(2, $d->id());
		$this->assertEquals(1, $d->TestOne->id());
	}

	public function testDecoratorWillAutoCreateNestedObjects() {
		$data = array(
			"TestOne" => array("id" => 1, "content" => "Sample content"),
			"TestTwo" => array(
				array("id" => 2, "content" => "Different content"),
				array("id" => 3, "content" => "Something content")
			)
		);
		$d = new Decorator($data, "TestOne");
		$this->assertEquals(1, $d->id());
		$this->assertEquals(2, count($d->TestTwo));
		$this->assertEquals("Something content", $d->TestTwo[1]->content());
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

