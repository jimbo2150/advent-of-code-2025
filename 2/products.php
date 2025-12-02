<?php

$input_file = __DIR__ . '/input.csv';
$file_handle = fopen($input_file, 'r');

$valid_ids = 0;
$invalid_ids = 0;
$invalid_sum = 0;

function is_valid(string $value) {
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

while (($row = fgetcsv($file_handle, null, ",")) !== FALSE) {
	foreach($row as $entry) {
		$range = explode('-', $entry);
		foreach(range($range[0], $range[1]) as $value) {
			// Remove leading zeroes
			$value = ltrim($value, '0');
			// Check validity
			if(is_valid($value)) {
				$valid_ids += 1;
				continue;
			}
			$invalid_ids += 1;
			$invalid_sum += intval($value);
		}
	}
}

echo 'Valid ids: ', $valid_ids, PHP_EOL;
echo 'Invalid ids: ', $invalid_ids, PHP_EOL;
echo 'Invalid sum: ', $invalid_sum, PHP_EOL;