<?php
// header_json(); print_r($_SESSION); exit();
$billing_info = ecatalog_get_product_billing_period();
// header_json(); print_r($billing_info); exit();
?>

<?php
get_header('product');
?>

<!-- Main Content -->
<div class="container">
	<div class="product-main-container" style="max-width: 1000px; margin: auto;">
		<div class="row">
			<div class="col-sm-12">
				<?php if (isset($data['submitted_product']['product']['product_name'])): ?>
				<p>"<?php echo $data['submitted_product']['product']['product_name']; ?>" has beed added to Cart. <a href="<?php echo cms_get_cart_url() ?>">View Cart</a></p>
				<?php endif ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="text-center">
					<img src="<?php echo bloginfo("baseurl"); ?><?php echo ecatalog_get_featured_image(); ?>" alt="" class="product-img">
				</div>
				<?php echo ecatalog_get_product_excerpt() ?>
			</div>
			<div class="col-lg-6">
				<?php if (isset($data['product']['product_name'])):?>
					<div class="product-name-container">
						<h2 style="font-size: 35px;"><?php echo $data['product']['product_name']; ?></h2>
					</div>
				<?php endif; ?>
				<?php if (isset($data['product']['sku'])):?>
					<div class="product-sku-container">
						<span><small><em><b>Product Code: </b> <?php echo $data['product']['sku']; ?></em></small></span>
					</div>
				<?php endif; ?>

				<hr>

				<?php if (isset($data['product']['price'])):?>
					<div class="product-price-container">
						<h3>Price: <?php echo  get_system_option(array("option_name" => "currency_symbol")) . " " . $data['product']['price']; ?></h3>
					</div>
				<?php endif; ?>

				<div class="product-categories-container">
					<strong>Categories: </strong>
					<span>
						<?php $cat_ctr = 0; ?>
						<?php foreach (ecatalog_get_categories(array("product_id"=>ecatalog_get_product_detail('id'))) as $key => $value): ?>
						<?php echo $cat_ctr++ > 0 ? ',' : '' ?><a href="<?php echo bloginfo('baseurl') . "product-category/" . $value['url_slug']; ?>"><?php echo $value['category_name']; ?></a>
						<?php endforeach ?>
					</span>
				</div>

				<hr>
				<?php if (isset($data['product']['quantity'])):?>
					<div class="product-name-container">
					<?php include __DIR__ . "/fragments/product-subscription-button.php" ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs"> 
					<ul class="nav nav-tabs" id="myTabs" role="tablist"> 
					<?php $isActiveTab = false; ?>
					<?php foreach (ecatalog_get_product_tabs() as $key => $value): ?>
						<li role="presentation" class="<?php echo !$isActiveTab ? 'active' : ''; ?>">
							<a href="#<?php echo "tab-{$value['id']}"; ?>" id="<?php echo "tab-nav-{$value['id']}"; ?>" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><?php echo $value['tab_title']; ?></a>
						</li> 
					<?php $isActiveTab = true; ?> 
					<?php endforeach ?> 
					</ul> 
					<div class="tab-content" id="myTabContent"> 
					<?php $isActiveTab = false; ?>
					<?php foreach (ecatalog_get_product_tabs() as $key => $value): ?>
						<div class="tab-pane fade <?php echo !$isActiveTab ? 'active in' : ''; ?>" role="tabpanel" id="<?php echo "tab-{$value['id']}"; ?>" aria-labelledby="tab"> 
							<?php echo "{$value['tab_content']}"; ?>
						</div>
						<?php $isActiveTab = true; ?> 
					<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>