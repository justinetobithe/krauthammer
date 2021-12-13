<?php if (count($products)): ?>
	
<form action="<?php echo cms_get_payment_url() ;?>" method="post">
	<div class="row">
		<div class="col-sm-12">
			<h2>Payment Method</h2>
			<hr>
			<div class="payment-method-container">
			<?php $checked = false; ?>
			<?php foreach ($payment_methods as $key => $value): ?>
				<div class="payment-method">
					<h5> 
					<label><input type="radio" name="selected-method" value="<?php echo $value['detail']->id; ?>" id="<?php echo $value['detail']->id; ?>" <?php echo !$checked ? "checked" : ""; $checked = true; ?>> <?php echo $value['detail']->display_name; ?><br>

						<?php if (isset($value['options']['offline_1_instruction'])): ?>
							<span><small><?php echo $value['options']['offline_1_instruction']->option_value; ?></small></span>
						<?php endif ?>
						<?php if (isset($value['options']['offline_2_instruction'])): ?>
							<span><small><?php echo $value['options']['offline_2_instruction']->option_value; ?></small></span>
						<?php endif ?>
						<?php if (isset($value['options']['offline_3_instruction'])): ?>
							<span><small><?php echo $value['options']['offline_3_instruction']->option_value; ?></small></span>
						<?php endif ?>
					</label>
					</h5>
				</div>
			<?php endforeach ?>
			</div>
		</div>
	</div>

	<hr>
	<div class="text-center">
		<button value="submit" name="product-customer-detail-submit" class="btn btn-primary">Select Payment</button>
	</div>
</form>
<?php else: ?>
	<p>No product in you cart.</p>
<?php endif ?>
