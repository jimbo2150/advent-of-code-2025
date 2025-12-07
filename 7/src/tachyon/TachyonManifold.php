<?php

class TachyonManifold {
	private mixed $input;

	private mixed $output;

	private int $split_total;

	public function __construct(string $input, string $output) {
		$this->input = fopen($input, 'r');
		$this->output = fopen($output, 'w');
	}

	public function close_input() {
		if(is_resource($this->input)) {
			fclose($this->input);
		}
	}

	public function close_output() {
		if(is_resource($this->output)) {
			fclose($this->output);
		}
	}

	public function __destruct() {
		$this->close_input();
		$this->close_output();
	}

	public function get_splits():int|null {
		return $this->split_total;
	}

	public function process() {
		$this->split_total = 0;
		rewind($this->input);
		rewind($this->output);
		$tachyon_pos = [];
		while(($row = fgets($this->input)) !== false && ($row = str_split(rtrim($row, "\n\r")))) {
			$output_row = [];
			foreach($row as $loc_idx => $content) {
				$tachyon_desc = ($tachyon_pos[$loc_idx] ?? false) == true;
				if(in_array($content, ['^'])) {
					unset($tachyon_pos[$loc_idx]);
					$tachyon_pos[$loc_idx-1] = $tachyon_pos[$loc_idx + 1] = true;
					if($tachyon_desc) {
						$this->split_total += 1;
					}
					if(isset($output_row[$loc_idx-1]) && false === in_array($output_row[$loc_idx-1], ['S', '^', '|'])) {
						$output_row[$loc_idx-1] = '|';
					}
					$tachyon_desc = false;
				} else if(in_array($content, ['S'])) {
					$tachyon_pos[$loc_idx] = true;
				}
				$output_row[] = $tachyon_desc ? '|' : $content;
			}
			fwrite($this->output, implode('', $output_row) . PHP_EOL);
		}
	}
}
