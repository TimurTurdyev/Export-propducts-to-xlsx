<?php
if (php_sapi_name() !== 'cli') die('Access Denied!');
require './../config.php';
require 'vendor/autoload.php';

// $STDOUT = fopen('/tmp/php_stdout.txt', 'a');
$product = new Classes\ProductController('files/prime-wood.products.xlsx');
$product->init();
// fclose($STDOUT);
