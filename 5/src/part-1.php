<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$total_fresh_ingredients = 0;

$ranges = [];

function add_range(Range $range, array &$ranges): int|true {
	foreach($ranges as $idx => $stored_range) {
		$new_range = $range->merge($ranges[$idx]);
		if($new_range !== false) {
			$ranges[$idx] = $new_range;
			return $idx;
		}
	}
	$ranges[] = $range;
	return true;
}

function remerge_ranges(array &$ranges): int {
	static $loops = 0;
	$has_merges = true;
	while($has_merges) {
		$loops++;
		$has_merges = false;
		foreach($ranges as $idx => $range) {
			if(false == is_bool($new_idx = add_range($range, $ranges)) && $idx !== $new_idx) {
				$has_merges = true;
				unset($ranges[$idx]);
			}
		}
	}
	return $loops;
}

function parse_range(string $range): Range|false {
	if(false == preg_match('/([0-9]+)\-([0-9]+)/', $range, $matches)) {
		return false;
	}
	return new Range(intval($matches[1]), intval($matches[2]));
}

function process_ingredient(int $id, array $ranges): bool {
	/** @var Range $range */
	foreach($ranges as $range) {
		if($range->contains($id)) {
			return true;
		}
	}
	return false;
}

$range_count = 0;
$remerge_loops = 0;

while(($row = fgets($GLOBALS['input_file'])) !== false) {
	$row = trim($row);
	if(empty($row)) {
		usort($ranges, function(Range $a, Range $b) {
			if ($a->getStart() == $b->getStart()) {
				return 0;
			}
			return ($a->getStart() < $b->getStart()) ? -1 : 1;
		});
		if(!empty($ranges)) {
			$remerge_loops = remerge_ranges($ranges);
		}
		continue;
	}
	if(str_contains($row, '-')) {
		$range_count += 1;
		if($range = parse_range($row)) {
			$ranges[] = $range;
		}
	} else {
		if(process_ingredient(intval($row), $ranges)) {
			$total_fresh_ingredients += 1;
		}
	}
}

echo 'Part 1: : Total fresh ingredients: ', $total_fresh_ingredients, PHP_EOL;