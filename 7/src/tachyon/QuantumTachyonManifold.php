<?php

class QuantumTachyonManifold {
	private mixed $input;

	private string $output_dir;

	private array $paths = [];

	/** @var array<Fiber> */
	private array $fibers = [];

	private int $path_total;

	public function __construct(string $input, string $output_dir) {
		$this->input = fopen($input, 'r');
		$this->output = realpath($output_dir);
	}

	public function close_input() {
		if(is_resource($this->input)) {
			fclose($this->input);
		}
	}

	private static function close_resource(mixed $resource) {
		if(is_resource($resource)) {
			fclose($resource);
		}
	}

	public function __destruct() {
		$this->close_input();
		$has_running = true;
		while($has_running) {
			foreach($this->fibers as $idx => $fiber) {
				$has_running = false;
				if($fiber->isRunning()) {
					$has_running = true;
				}
				if($fiber->isSuspended()) {
					$fiber->throw(new TerminationException('Computer, end program.'));
				}
				if($fiber->isTerminated()) {
					unset($this->fibers[$idx]);
				}
			}
		}
	}

	public function get_paths():int|null {
		return $this->path_total;
	}

	private function process_path($loc, $row) {
		try {
			var_dump($loc);
			Fiber::suspend('next');
 		} catch(TerminationException $e) {
			
		}
	}

	private function create_fiber(): string {
		$uuid = uniqid('', true);
		$this->fibers[$uuid] = new Fiber($this->process_path(...));
		return $uuid;
	}

	private function run_fiber(string $uuid, ...$arguments):mixed {
		return $this->fibers[$uuid]->start(...$arguments);
	}

	public function process() {
		$this->path_total = 0;
		rewind($this->input);
		while(($row = fgets($this->input)) !== false && ($row = str_split(rtrim($row, "\n\r")))) {
			foreach($row as $loc_idx => $content) {
				if(in_array($content, ['^', 'S'])) {
					$this->run_fiber($this->create_fiber(), $loc_idx, $row);
				}
			}
		}
	}
}

class TerminationException extends Exception {

}
