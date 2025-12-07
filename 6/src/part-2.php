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
				'rtl_stack' => [],
				'stack_width' => 0,
				'operation' => null
			];
			$problems[$idx]['stack'][] = $value = intval($problem);
			$problems[$idx]['stack_width'] = max($problems[$idx]['stack_width'], strlen($value));
		} else {
			// Operation
			$problems[$idx]['operation'] = Operation::tryFrom($problem);
		}
	}
}

$grand_total = 0;

foreach($problems as $idx => $problem) {
	$ltr = [];
	/** @var array<int> $problem['stack'] */
	foreach($problem['stack'] as $entry) {
		$ltr []= str_split(str_pad($entry, $problem['stack_width'], ' ', STR_PAD_RIGHT));
	}
	foreach(range(count($ltr[0])-1, 0) as $col) {
		$problem['ltr_stack'][] = intval(implode('', array_column($ltr, $col)));
	}
	$grand_total += $problem['total'] = $problem['operation']->perform(...$problem['ltr_stack']);
 	echo 'Part 2: Problem ', $idx + 1, ' (' . $problem['operation']->to_string(...$problem['ltr_stack']) . ') total: ', $problem['total'], PHP_EOL;
}

echo 'Part 2: Grand total: ', $grand_total, PHP_EOL;
