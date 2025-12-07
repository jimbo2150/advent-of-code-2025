<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
//$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$manifold = new QuantumTachyonManifold($input_file_path);
$manifold->process();

echo 'Part 2: Quantum Tachyon Path Total: ', $manifold->get_paths(), PHP_EOL;
