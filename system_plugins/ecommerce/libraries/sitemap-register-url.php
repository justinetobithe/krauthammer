<?php
/*
To add urls to the sitemap, use the following function: $this->sitemap_reguster_ulr([@Array urls], [@String type])
*/
$urls = array();
$this->sitemap_register_url($urls, "products");