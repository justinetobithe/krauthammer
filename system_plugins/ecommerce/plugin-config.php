<?php
/*
Plugin Name: Ecommerce
Type: ECOMMERCE
Sitemap Format: [baseurl]/[controlle]/urlslug()

Templates: Product Cart, page-cart
Templates: Product Enquire, page-payment
Templates: Product Checkout, page-checkout
Templates: Product Confirmed, page-confirmed
Templates: Product Confirmation, page-confirmation
Templates: Product Homepage, page-product-homepage

Menu: ["Products", "products", "products/", "icon-laptop", "ECOMMERCE", ["products"], ["products/*", "product-brand/*"]]
Sub: ["Add Product", "products-add", "products/add/", "", "", [], ["products/add/*"]]
Sub: ["Products", "products-list", "products/", "", "", [], ["products/*", "products/edit/*", "products/add/!", "products/categories/!"]]
Sub: ["Product Categories", "products-categories-list", "products/categories/", "", "", [], ["products/categories/*"]]
Sub: ["Product Brand", "products-list", "product-brand/", "", "", [], ["product-brand/*"]]


Notes:
ecatalog_get_product_billing_period()
-Get the billing period setting of the current product.

*/