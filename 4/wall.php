<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$input_file = __DIR__ . '/data/input.txt';
$GLOBALS['input_file'] = fopen($input_file, 'r');

// Buffer the number of spaces on each size plus the current side
const BUFFER_ROW_SIZE = 3;

const MAX_ADJ_ROLLS = 3;

define('HALF_ROW_BUFFER', intval(BUFFER_ROW_SIZE / 2));

require_once __DIR__ . '/src/part-1.php';

require_once __DIR__ . '/src/part-2.php';

