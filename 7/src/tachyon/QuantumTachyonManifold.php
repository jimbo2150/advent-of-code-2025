<?php

class QuantumTachyonManifold {
	private mixed $input;

	private array $path_sums = [];

	private int $path_total = 0;

	public function __construct(string $input) {
		$this->input = fopen($input, 'r');
	}

	public function close_input() {
		if(is_resource($this->input)) {
			fclose($this->input);
		}
	}

	public function __destruct() {
		$this->close_input();
	}

	public function get_paths():int|null {
		return $this->path_total;
	}

	public function process() {
		$this->split_total = 0;
		rewind($this->input);
		$tachyon_pos = [];
		while(($row = fgets($this->input)) !== false && ($row = str_split(rtrim($row, "\n\r")))) {
			foreach($row as $loc_idx => $content) {
				$tachyon_desc = ($tachyon_pos[$loc_idx] ?? false) == true;
				if(in_array($content, ['^'])) {
					unset($tachyon_pos[$loc_idx]);
					$tachyon_pos[$loc_idx-1] = $tachyon_pos[$loc_idx + 1] = true;
					if($tachyon_desc) {
						$prev_paths = $this->path_sums[$loc_idx] ?? 0;
						$this->path_sums[$loc_idx] = 0;
						$this->path_sums[$loc_idx-1] = ($this->path_sums[$loc_idx-1] ?? 0) + $prev_paths;
						$this->path_sums[$loc_idx+1] = ($this->path_sums[$loc_idx+1] ?? 0) + $prev_paths;
					}
					if(isset($output_row[$loc_idx-1]) && false === in_array($output_row[$loc_idx-1], ['S', '^', '|'])) {
						$output_row[$loc_idx-1] = '|';
					}
					$tachyon_desc = false;
				} else if(in_array($content, ['S'])) {
					$tachyon_pos[$loc_idx] = true;
					$this->path_sums[$loc_idx] = ($this->path_sums[$loc_idx] ?? 0) + 1;
				}
			}
		}
		$this->path_total = array_sum($this->path_sums);
	}
}

class TerminationException extends Exception {

}
