# CakePHP Model Decorators Plugin

This is an experimental plugin to wrap `Model::find()` results in objects. These decorator objects should be used to render the model data in the view and possibly render some markup around the data. Could also implement some custom methods to rended blocks of markup that depend on model data.

## Example

### Current setup

In the controller you'd find a product record and set that to the view.

	// Controller
	$this->set("product", $this->Product->read(null, $id));

If you need to display a different class for in the span tag that wraps the price, you'd have to have some view logic.

	// View
	<?php if ($product["Product"]["price"] <= 10): ?>
		<span class="redColor">
	<?php elseif ($product["Product"]["price"] > 10 && $product["Product"]["price"] <= 25): ?>
		<span class="yellowCollor">
	<?php else: ?>
		<span class="greenColor">
	<?php endif; ?>
	<?php echo $product["Product"]["price"]; ?>
	</span>

### Using Decorators

Using decorators would start at the controller. Creating a decorator using the `DecoratorComponent::create()` method takes a decorator name and an array of data. The method will return an instance of the decorator class.

	// Controller
	$product = $this->Decorator->create("Product", $this->Product->read(null, $id));
	$this->set(compact("product"));

Then in the decorator class (ProductDecorator in View/Decorator) you would define a method and will perform the logic and return the HTML string with the proper class.

	// ProductDecorator Class
	public function price() {
		$price = $this->_raw("price");
		$class = "greenColor";
		if ($price <= 10) {
			$class = "redColor";
		} elseif ($price > 10 && $price <= 25) {
			$class = "yellowColor";
		}
		return "<span class=\"{$class}\">{$price}</span>";
	}

Now all of the logic is removed from the view and all you've got to render is the result of the method call.

	// View
	<?php echo $product->price(); ?>

## Decorator Class Methods

There are 2 method currently in the Decorator base class.

**Decorator::parseData(array $data)**

By default this method will check the data passed in to see if the key (the class name without Decorator) exists, and returns it. In the case of $data = array("ModelName" => array("id" => 2)), and the decorator class name is ModelNameDecorator, the method will return array("id" => 2). The return value from this method is assigned to the `Decorator::model` property. Overwrite this method to parse the data in your own custom way.

**Decorator::raw(string $key)**

You have access to the keys in your model record array by doing something like this: `$decorator->price()` (by using __call()). In the case of when the `price()` method is actually defined with some custom logic, you can use the `raw()` method to get at the original value.

Note in the examples above, I used the `_raw()` method. The `raw()` method uses the `_raw()` method internally. This way you could overwrite the `raw()` method if you need to, and then make a different way of getting at the `_raw()` protected method.

## TODOs

- Build decorators for related model data
- Include view helpers in the decorator