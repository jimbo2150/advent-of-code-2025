<?php

$input_file = __DIR__ . '/input.txt';
$file_handle = fopen($input_file, 'r');

const LOCK_MAX = 99;
const DEV = true;
const LOCK_MAX_PLUS_ONE = LOCK_MAX + 1;

enum LockDirection: string {
	case Left = 'L';
	case Right = 'R';
}

// Starting location
$lock_location = 50;
$zero_count = 0;

$rotate_lock = function(LockDirection $direction, int $clicks) use (&$lock_location, &$zero_count) {
	$new_lock_location = $direction === LockDirection::Right ?
		(($lock_location + $clicks) % LOCK_MAX_PLUS_ONE) :
		((($lock_location - $clicks) % LOCK_MAX_PLUS_ONE + LOCK_MAX_PLUS_ONE) % LOCK_MAX_PLUS_ONE);
	if(DEV) {
		echo 'Lock location changed (' . $direction->name . ' ' . $clicks . ') from ', $lock_location, ' to ', $new_lock_location, PHP_EOL;
	}
	if($new_lock_location === 0) {
		$zero_count += 1;
		if(DEV) {
			echo 'Added 1 to the password: ', $zero_count, PHP_EOL;
		}
	}
	$lock_location = $new_lock_location;
};

while (!feof($file_handle)) {
	preg_match('/([LR])([0-9]+)/', fgets($file_handle), $matches);
	if(!isset($matches[2])) {
		continue;
	}
	$matches[1] = LockDirection::tryFrom($matches[1]);
	if(false === ($matches[1] instanceof LockDirection)) {
		continue;
	}
	$matches[2] = intval($matches[2]);
	$rotate_lock($matches[1], $matches[2]);
	if(DEV) {
		sleep(0.7);
	}
}

echo 'Zero count (password): ', $zero_count, PHP_EOL;
