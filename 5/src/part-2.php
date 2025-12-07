<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$total_fresh_ingredients = 0;

echo 'Part 2: : Total fresh ingredients: ', $total_fresh_ingredients, PHP_EOL;

fclose($GLOBALS['input_file']);
