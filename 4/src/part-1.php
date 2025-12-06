<?php

require_once __DIR__ . '/shared.php';

$grid_buffer = [];

$grid_pos = ['x' => 0, 'y' => 0];

$total_rolls_accessible = 0;

$loop_column = function() use (&$grid_buffer, &$grid_pos, &$total_rolls_accessible): void {
	foreach(range(0, count($grid_buffer[$grid_pos['y']])) as $col) {
		$grid_pos['x'] = $col;
		// Check current offset and update grid
		check_and_update_at_position(
			$grid_buffer,
			$grid_pos['x'],
			$grid_pos['y'],
			$total_rolls_accessible
		);
	}
};

$loop = 0;

while (advance_buffer($grid_buffer, $GLOBALS['input_file']) && count($grid_buffer) > 0) {
	if(isset($grid_buffer[$grid_pos['y']])) {
		while($grid_pos['y'] < intval(BUFFER_ROW_SIZE / 2)) {
			$loop_column();
			$grid_pos['y'] += 1;
		}
		$loop_column();
	}
	// Write next item to output buffer
	write_to_output($output = array_shift($grid_buffer), $GLOBALS['output_file']);
}

echo 'Part 1: : Total rolls accessible: ', $total_rolls_accessible, PHP_EOL;