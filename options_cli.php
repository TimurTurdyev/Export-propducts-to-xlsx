<?php
if (php_sapi_name() !== 'cli') {
    die('Access Denied!');
}

ini_set('memory_limit', '2048M');

require './../config.php';
require 'vendor/autoload.php';
$STDOUT = fopen('/tmp/php_stdout.txt', 'a');
$product = new Classes\ProductOptionsController('files/prime-wood.options.xlsx');
$product->init();
print_r('Отчет успено создан!');
fclose($STDOUT);
