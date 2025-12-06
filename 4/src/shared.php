<?php

function advance_buffer(array &$grid_buffer, mixed &$readable): true {
	if(false == is_resource($readable) || false === str_contains(stream_get_meta_data($readable)['mode'], 'r')) {
		throw new InvalidArgumentException('File must be a readable resource.');
	}
	$current_row_count = count($grid_buffer);
	$need_rows = 0;
	if($current_row_count < BUFFER_ROW_SIZE) {
		$need_rows = BUFFER_ROW_SIZE - $current_row_count;
	}
	while($need_rows-- > 0) {
		$next_line = fgets($readable);
		if($next_line !== false) {
			$grid_buffer []= mb_str_split(mb_rtrim($next_line));
		}
	}
	return true;
};

function check_individual_roll(array $grid, int $skip_row, int $skip_col): bool {
	$roll_count = 0;
	foreach($grid as $row_idx => $row) {
		foreach($row as $col_idx => $contents) {
			if($row_idx == $skip_row && $col_idx == $skip_col) {
				continue;
			}
			if(in_array($contents, ['@', 'x'])) {
				$roll_count += 1;
				if($roll_count > MAX_ADJ_ROLLS) {
					return false;
				}
			}
		}
	}
	return true;
}

function check_and_update_at_position(array &$grid_buffer, int $grid_pos_x, int $grid_pos_y, int &$total_rolls_accessible): void {
	$contents = $grid_buffer[$grid_pos_y][$grid_pos_x] ?? null;
	if(false == in_array($contents, ['@', 'x'])) {
		return;
	}
	$mini_grid = (function() use ($grid_buffer, $grid_pos_x, $grid_pos_y): array {
		$grid = [];
		foreach(range($grid_pos_y - 1, $grid_pos_y + 1) as $row_idx => $row) {
			foreach(range($grid_pos_x - 1, $grid_pos_x + 1) as $col_idx => $col) {
				if(!isset($grid_buffer[$row][$col])) {
					$grid[$row_idx][$col_idx] = '.';
					continue;
				}
				$grid[$row_idx][$col_idx] = $grid_buffer[$row][$col];
			}
		}
		return $grid;
	})();
	if(check_individual_roll($mini_grid, 1, 1)) {
		$total_rolls_accessible += 1;
		$grid_buffer[$grid_pos_y][$grid_pos_x] = 'x';
	}
}

function write_to_output(array $output, mixed $writable) {
	if(
		false == is_resource($writable) ||
		(
			false === str_contains(stream_get_meta_data($writable)['mode'], 'a') &&
			false === str_contains(stream_get_meta_data($writable)['mode'], 'w')
		)
	) {
		throw new InvalidArgumentException('File must be a writable resource.');
	}
	fwrite($writable, implode('', $output) . PHP_EOL);
}

function print_grid_buffer(array $grid_buffer): void {
	foreach($grid_buffer as $line) {
		echo implode('', $line), PHP_EOL;
	}
}