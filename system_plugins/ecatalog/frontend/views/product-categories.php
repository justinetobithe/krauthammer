<?php
/*
Template Name: Blog Page
*/ 

get_header('product-category');

$tree = cms_get_product_category_tree($data['current_category']->id);
$category_detail = $data['current_category'];
$sub_categories = cms_get_sub_categories( $tree );
$products = cms_get_category_products( $tree );
$product_base_url = get_system_option('product_url' . (cms_get_language()!=cms_get_language_reserved()?'_'.cms_get_language():''));
$product_base_url = $product_base_url!=''?$product_base_url:'proudcts';
?>

<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10">
			<h1 class="post-title">
				Product Category: <?php echo $category_detail->category_name; ?>
			</h1>
			<hr>

			<?php if (count($products)): ?>
				<h2>Products</h2>

				<?php foreach ($products as $key => $value): ?>
					<ul>
						<li><a href="<?php echo get_bloginfo('baseurl') . "{$product_base_url}/" . $value->url_slug; ?>"><?php echo $value->product_name; ?></a></li>
					</ul>
				<?php endforeach ?>
			<?php else: ?>
				<p>No Product</p>
			<?php endif ?>

			<?php if (count($sub_categories)): ?>
				<hr>
				<h2>Sub Categories</h2>
				<?php foreach ($sub_categories as $key => $value): ?>
				<ul>
					<li>
						<a href="<?php echo $value->final_url; ?>">
							<?php echo $value->category_name; ?>
						</a>
					</li>
				</ul>
				<?php endforeach ?>
			<?php else: ?>
				<p>No Sub Categories</p>
			<?php endif ?>

		</div>
		<div class="col-lg-4 col-md-2">
			<?php get_sidebar('product'); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>