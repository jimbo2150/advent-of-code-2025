<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$lines = file($input_file_path, FILE_IGNORE_NEW_LINES);
$height = count($lines);
$width = max(array_map('strlen', $lines));

$grid = [];
foreach($lines as $row => $line) {
	$grid[$row] = str_split(str_pad($line, $width, ' '));
}

$separators = [];
for($col = 0; $col < $width; $col++) {
	$is_sep = true;
	for($row = 0; $row < $height; $row++) {
		if($grid[$row][$col] !== ' ') {
			$is_sep = false;
			break;
		}
	}
	if($is_sep) $separators[] = $col;
}

$problems = [];
$start = 0;
foreach($separators as $sep) {
	if($sep > $start) {
		$problems[] = ['start' => $start, 'end' => $sep - 1];
	}
	$start = $sep + 1;
}
if($start < $width) {
	$problems[] = ['start' => $start, 'end' => $width - 1];
}

$parsed_problems = [];
foreach($problems as $p) {
	$numbers = [];
	for($col = $p['start']; $col <= $p['end']; $col++) {
		$digits = '';
		for($row = 0; $row < $height - 1; $row++) {
			$char = $grid[$row][$col];
			if($char !== ' ') $digits .= $char;
		}
		if($digits !== '') $numbers[] = intval($digits);
	}
	$op_char = $grid[$height - 1][$p['start']];
	$operation = Operation::tryFrom($op_char);
	$parsed_problems[] = [
		'numbers' => $numbers,
		'operation' => $operation
	];
}

$grand_total = 0;
$idx = count($parsed_problems);
foreach(array_reverse($parsed_problems) as $problem) {
	$total = $problem['operation']->perform(...$problem['numbers']);
	$grand_total += $total;
}

echo 'Part 2: Grand total: ', $grand_total, PHP_EOL;
