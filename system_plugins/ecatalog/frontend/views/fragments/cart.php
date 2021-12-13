<?php if (count($products)): ?>
	
<div class="row">
	<div class="col-sm-12">
		<h2>Cart Detail</h2>
		<?php 
			$sub_total = 0;
			$total = 0;
		?>
		<table id="product-cart" class="product-cart-table">
		<thead>
			<tr>
				<th>Product Name</th>
				<th>Price</th>
				<th>SKU</th>
				<th>Quantity</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($products as $key => $value): ?>
		<?php
			$_total = intval($value['quantity']) * intval($value['product']['price']);
			$sub_total += $_total;
			$total += $_total;
		?>
			<tr>
				<td><a href="<?php echo get_bloginfo("baseurl") . "products/" . $value['product']['url_slug'] ?>"><?php echo $value['product']['product_name'] ?></a></td>
				<td><?php echo $value['product']['price'] ?></td>
				<td><?php echo $value['product']['sku'] ?></td>
				<td><?php echo $value['quantity'] ?></td>
				<td><?php echo $_total; ?></td>
				<td>
					<a href="<?php echo cms_get_cart_url() . "?action=remove&product=" . $value['product']['url_slug'] ?>" class="remove-cart-item" data-value="<?php echo $value['product']['id'] ?>"><small>Remove</small></a>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
		</table>
	</div>
</div>
<br>
<div class="row">
	<div class="col-sm-6">
		<h2>Cart Total</h2>
		<table id="product-cart-total" class="product-cart-table">
		<tbody>
			<tr>
				<td><b>SubTotal</b></td>
				<td><?php echo $sub_total ?></td>
			</tr>
			<tr>
				<td><b>Total</b></td>
				<td><?php echo $total ?></td>
			</tr>
		</tbody>
		</table>
	</div>
</div>

<hr>
<div class="text-center">
	<a href="<?php echo cms_get_checkout_url() ?>" class="btn btn-success">Proceed Checkout</a>
</div>
<?php else: ?>
	<p>No product in you cart.</p>
<?php endif ?>
