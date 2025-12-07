<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$total_fresh_ingredient_ids = 0;

$ranges = [];

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
		break;
	}
	if(str_contains($row, '-')) {
		$range_count += 1;
		if($range = parse_range($row)) {
			$ranges[] = $range;
		}
	}
}

foreach($ranges as $range) {
	/** @var Range $range */
	$total_fresh_ingredient_ids += $range->getEnd() - $range->getStart();
}

echo 'Part 2: Total fresh ingredient IDs (current): ', $total_fresh_ingredient_ids, PHP_EOL;
echo 'Part 2: Total fresh ingredient IDs (with +1): ', $total_with_plus_one, PHP_EOL;
