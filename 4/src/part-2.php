<?php

require_once __DIR__ . '/shared.php';

$output_file_path = __DIR__ . '/../data/p2-output-1.txt';
$GLOBALS['output_file'] = fopen($output_file_path, 'w');

$grid_buffer = [];

$grid_pos = ['x' => 0, 'y' => 0];

$total_rolls_removed = 0;

$loop_column = function(int &$rolls_removed) use (&$grid_buffer, &$grid_pos): void {
	foreach(range(0, count($grid_buffer[$grid_pos['y']])) as $col) {
		$grid_pos['x'] = $col;
		// Check current offset and update grid
		p2_check_and_update_at_position(
			$grid_buffer,
			$grid_pos['x'],
			$grid_pos['y'],
			$rolls_removed
		);
	}
};

$loop = 0;

$diff = 1;

$run_rolls_removed = 1;
$run = 1;

rewind($GLOBALS['input_file']);

while (advance_buffer($grid_buffer, $GLOBALS['input_file']) && count($grid_buffer) > 0) {
	if(isset($grid_buffer[$grid_pos['y']])) {
		while($grid_pos['y'] < intval(BUFFER_ROW_SIZE / 2)) {
			$loop_column($run_rolls_removed);
			$grid_pos['y'] += 1;
		}
		$loop_column($run_rolls_removed);
	}
	// Write next item to output buffer
	write_to_output($output = array_shift($grid_buffer), $GLOBALS['output_file']);
}

$total_rolls_removed += $run_rolls_removed;
echo 'Run ', $run++, '; Rolls removed this run:', $run_rolls_removed, PHP_EOL;

$GLOBALS['run_file'] = fopen($output_file_path, 'r');

while($run_rolls_removed > 0) {
	$run_rolls_removed = 0;

	$GLOBALS['run_file'] = fopen(__DIR__ . '/../data/p2-output-' . ($run-1) . '.txt', 'r');
	rewind($GLOBALS['run_file']);
	fclose($GLOBALS['output_file']);
	$output_file_path = __DIR__ . '/../data/p2-output-' . $run . '.txt';
	$GLOBALS['output_file'] = fopen($output_file_path, 'w');

	while (advance_buffer($grid_buffer, $GLOBALS['run_file']) && count($grid_buffer) > 0) {
		if(isset($grid_buffer[$grid_pos['y']])) {
			while($grid_pos['y'] < intval(BUFFER_ROW_SIZE / 2)) {
				$loop_column($run_rolls_removed);
				$grid_pos['y'] += 1;
			}
			$loop_column($run_rolls_removed);
		}
		// Write next item to output buffer
		write_to_output($output = array_shift($grid_buffer), $GLOBALS['output_file']);
	}

	$total_rolls_removed += $run_rolls_removed;
	echo 'Part 2: Run ', $run++, '; Rolls removed this run:', $run_rolls_removed, PHP_EOL;
}

echo 'Part 2: : Total rolls removed: ', $total_rolls_removed, PHP_EOL;
