<?php
get_header('product');
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10">
			<h2>Confirmation Page</h2>
			<br>
			<div class="container-order-detail">
				<h3>Summary</h3>

				<table class="table">
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Sub Total</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$cart = cart_get_products();
					cms_product_set_other_fees(array( 'shipping' => $cart['shipping_default']['rate_amount'] ));
					?>
					<?php foreach ($cart['cart_items'] as $key => $value): ?>
						<tr>
							<td><?php echo $value['product']['product_name'] ?></td>
							<td><?php echo $value['quantity'] ?></td>
							<td><?php echo $value['product']['price'] ?></td>
							<td><?php echo number_format(($value['quantity'] * $value['product']['price']), 2, '.', ',') ?></td>
						</tr>
					<?php endforeach ?>
					<?php if ($cart['shipping_enable']): ?>
						<tr>
							<td>Shipping Fee</td>
							<td></td>
							<td></td>
							<td><?php echo $cart['shipping_default']['rate_amount'] ?></td>
						</tr>
					<?php endif ?>
						<tr>
							<td>Total</td>
							<td></td>
							<td></td>
							<td><?php echo number_format($cart['cart_total'], 2, '.', ',') ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>
			<?php echo cms_generate_payment_button(); ?>
		</div>
		<div class="col-lg-4 col-md-2">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>