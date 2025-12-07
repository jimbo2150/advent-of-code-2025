<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
//$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$manifold = new TachyonManifold($input_file_path, __DIR__ . '/../data/p1_output.txt');
$manifold->process();

echo 'Part 1: Tachyon split total: ', $manifold->get_splits(), PHP_EOL;