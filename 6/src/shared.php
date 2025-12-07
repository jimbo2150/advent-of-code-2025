<?php

enum Operation: string {
	case ADDITION = '+';
	case SUBTRACTION = '-';
	case MULTIPLICATION = '*';
	case DIVISION = '/';

	public function perform(int|float ...$values): int|float {
		return match($this) {
			self::ADDITION =>
				array_reduce($values, fn($carry, $item) => $carry + $item, 0),
			self::SUBTRACTION =>
				array_reduce($values, fn($carry, $item) => $carry - $item, 0),
			self::MULTIPLICATION =>
				array_reduce($values, fn($carry, $item) => $carry * $item, 1),
			self::DIVISION =>
				array_reduce(
					$values,
					fn($carry, $item) => is_null($carry) ? $item : $carry / $item,
					null
				),
		};
	}

	public function operator(): string {
		return match($this) {
			self::ADDITION => '+',
			self::SUBTRACTION => '-',
			self::MULTIPLICATION => '*',
			self::DIVISION => '/',
		};
	}

	public function to_string(int|float ...$values): string {
		return implode(
			' ' . $this->operator() . ' ',
			$values
		);
	}

	public function print(int|float ...$values): void {
		echo $this->to_string($values);
	}
}