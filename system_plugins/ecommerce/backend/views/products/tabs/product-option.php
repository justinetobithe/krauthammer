<?php
/*
Title: Product Option
Order: 7
ID: product_options
*/ 
?>

<div id="product_options" class="tab-pane">
	<div class="well well-small">
		<a href="javascript:void(0)" class="btn btn-success btn-small" id="modal-btn-product-options"><i class="icon icon-plus"></i> Edit Option</a>
	</div>

	<div id="product-option-container" style="display: none;">
		<div class="product-option-container-header">
			<div class="row-fluid">
				<div class="span1 text-center"></div>
				<div class="span5">
					<label><b>Product Options</b></label>
				</div>
				<div class="span2">
					<b>SKU</b>
				</div>
				<div class="span2">
					<b>Quantity</b>
				</div>
				<div class="span2">
					<b>Price</b>
				</div>
			</div>
		</div>
		<div id="product-option-container-content"></div>
		<hr>
		<div>
			<button class="btn btn-success btn-small" id="product-option-save" data-loading-text="Loading..." type="button"><i class="icon icon-save"></i> Save</button>
		</div>
	</div>
</div>