<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$input_file = __DIR__ . '/input.txt';
$file_handle = fopen($input_file, 'r');

while (($row = fgets($file_handle)) !== FALSE) {
	
}

echo 'Part 1: : ', PHP_EOL;

echo 'Part 2: : ', PHP_EOL;