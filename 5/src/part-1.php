<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$total_fresh_ingredients = 0;

$ranges = [];

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