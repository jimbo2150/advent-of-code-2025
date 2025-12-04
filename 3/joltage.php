<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$input_file = __DIR__ . '/input.txt';
$file_handle = fopen($input_file, 'r');

$total_joltage = 0;

$selected_idx = [];
$needed_joltages = 1;

$determine_joltages = function(string $digit) {
	if($idx + 1 < $len) {
		if($digit > $joltage[0]) {
			$joltage[0] = $digit;
			$tens_selected_idx = $idx;
			$joltage[1] = 0;
			return;
		} 
	}
	if($digit > $joltage[1]) {
		$joltage[1] = $digit;
		$ones_selected_idx = $idx;
	}
};

while (($row = fgets($file_handle)) !== FALSE) {
	$digits = str_split(trim($row));
	$len = count($digits);
	$joltage = [0, 0];
	foreach($digits as $idx => $digit) {
		$determine_joltages($digit);
	}
	$total_joltage += intval(implode('', $joltage));
	$digits[$tens_selected_idx] = "\033[1m" . $digits[$tens_selected_idx] . "\033[0m";
	$digits[$ones_selected_idx] = "\033[1m" . $digits[$ones_selected_idx] . "\033[0m";
	//echo implode('', $digits), PHP_EOL, ' --- ', implode('', $joltage), PHP_EOL;
}

echo 'Part 1: : Total Joltage: ', $total_joltage, PHP_EOL;

echo 'Part 2: : ', PHP_EOL;