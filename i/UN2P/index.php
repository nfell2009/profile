<?php
/*
 * Welcome to Learn Lesson
 * This is very Simple PHP Code of Caching
 */
// Require Library
require_once("../phpfastcache/phpfastcache.php");
// simple Caching with:
$cache = phpFastCache();
// Try to get $products from Caching First
// product_page is "identity keyword";
$products = $cache->get("product_page");
if($products == null) {
    $products = "DB QUERIES | FUNCTION_GET_PRODUCTS | ARRAY | STRING | OBJECTS";
    $cache->set("product_page",$products , 7200);
}else{
	echo("nocache\n<br/>");
}
// use your products here or return it;
echo $products;
