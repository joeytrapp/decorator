# CakePHP Model Decorators Plugin

This is an experimental plugin to wrap `Model::find()` results in objects. These decorator objects should be used to render the model data in the view and possibly render some markup around the data. Could also implement some custom methods to rended blocks of markup that depend on model data.

## Example

### Current setup

	// Controller
	$this->set("product", $this->Product->read(null, $id));

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

	// Controller
	$product = $this->Decorator->create("Product", $this->Product->read(null, $id));
	$this->set(compact("product"));

	// ProductDecorator Class
	public function price() {
		$price = $this->raw("price");
		$class = "greenColor";
		if ($price <= 10) {
			$class = "redColor";
		} elseif ($price > 10 && $price <= 25) {
			$class = "yellowColor";
		}
		return "<span class=\"{$class}\">{$price}</span>";
	}

	// View
	<?php echo $product->price(); ?>


## TODOs

- Build decorators for related model data
- Include view helpers in the decorator