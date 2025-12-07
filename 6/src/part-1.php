<?php

require_once __DIR__ . '/shared.php';

$input_file_path = __DIR__ . '/../data/input.txt';
$GLOBALS['input_file'] = fopen($input_file_path, 'r');

$problems = [];

while(($row = fgets($GLOBALS['input_file'])) !== false) {
	$row = preg_split('/\s+/', trim($row));
	foreach($row as $idx => $problem) {
		if(is_numeric($problem)) {
			$problems[$idx] = $problems[$idx] ?? [
				'total' => 0,
				'stack' => [],
				'operation' => null
			];
			$problems[$idx]['stack'][] = intval($problem);
		} else {
			// Operation
			$problems[$idx]['operation'] = Operation::tryFrom($problem);
		}
	}
}

$grand_total = 0;

foreach($problems as $idx => $problem) {
	/** @var array<int> $problem['stack'] */
	$grand_total += $problem['total'] = $problem['operation']->perform(...$problem['stack']);
 	echo 'Part 1: Problem ', $idx + 1, ' (' . $problem['operation']->name . ') total: ', $problem['total'], PHP_EOL;
}

echo 'Part 1: : Grand total: ', $grand_total, PHP_EOL;