<?php

$input_file = __DIR__ . '/input.csv';
$file_handle = fopen($input_file, 'r');

$p1_valid_ids = 0;
$p1_invalid_ids = 0;
$p1_invalid_sum = 0;

$p2_valid_ids = 0;
$p2_invalid_ids = 0;
$p2_invalid_sum = 0;

function p1_is_valid(string $value) {
	$len = strlen($value);
	$isEven = $len % 2 == 0;
	// If value length is not even, it's valid
	if(false == $isEven) {
		return true;
	}
	// Split string in half and check if halves are equal
	$halfway = $len / 2;
	$split = str_split($value, $halfway);
	if($split[0] == $split[1]) {
		return false;
	}
	return true;
}

function p2_is_valid(string $value) {
	$len = strlen($value);
	if($len < 2) {
		return true;
	}
	foreach(range($len - 1, 1) as $str_len) {
		if($len % $str_len != 0) {
			continue;
		}
		$split = str_split($value, $str_len);
		if(count(array_unique($split)) == 1) {
			return false;
		}
	}
	return true;
}

while (($row = fgetcsv($file_handle, null, ",")) !== FALSE) {
	foreach($row as $entry) {
		$range = explode('-', $entry);
		foreach(range($range[0], $range[1]) as $value) {
			// Remove leading zeroes
			$value = ltrim($value, '0');
			// Check validity
			if(p1_is_valid($value)) {
				$p1_valid_ids += 1;
			} else {
				$p1_invalid_ids += 1;
				$p1_invalid_sum += intval($value);
			}
			if(p2_is_valid($value)) {
				$p2_valid_ids += 1;
			} else {
				$p2_invalid_ids += 1;
				$p2_invalid_sum += intval($value);
			}
		}
	}
}

echo 'Part 1: Valid ids: ', $p1_valid_ids, PHP_EOL;
echo 'Part 1: Invalid ids: ', $p1_invalid_ids, PHP_EOL;
echo 'Part 1: Invalid sum: ', $p1_invalid_sum, PHP_EOL;

echo 'Part 2: Valid ids: ', $p2_valid_ids, PHP_EOL;
echo 'Part 2: Invalid ids: ', $p2_invalid_ids, PHP_EOL;
echo 'Part 2: Invalid sum: ', $p2_invalid_sum, PHP_EOL;