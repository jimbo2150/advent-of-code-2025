<?php

require_once __DIR__ . '/src/shared.php';

function get_cpu_count_from_proc(): int {
    $cpu_count = 0;
    $cpuinfo_path = '/proc/cpuinfo';

    if (is_file($cpuinfo_path) && is_readable($cpuinfo_path)) {
        $cpuinfo = file_get_contents($cpuinfo_path);
        if ($cpuinfo !== false) {
            // Count lines starting with 'processor'
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $cpu_count = count($matches[0]);
        }
    }
    
    // Fallback to 1 if detection fails for any reason
    return $cpu_count > 0 ? $cpu_count : 1;
}

define(
	'NUM_OF_CPUS', 
	max(
		1,
		intval(
			(
				getenv('NUMBER_OF_PROCESSORS') ?:
				get_cpu_count_from_proc()
			)

		)
	)
);