<?php

require_once __DIR__ . '/utility/range.php';

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
		// Re-index array to avoid gaps
		$ranges = array_values($ranges);
	}
	return $loops;
}

function parse_range(string $range): Range|false {
	if(false == preg_match('/([0-9]+)\-([0-9]+)/', $range, $matches)) {
		return false;
	}
	return new Range(intval($matches[1]), intval($matches[2]));
}