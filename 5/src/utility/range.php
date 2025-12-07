<?php

class Range {
	public function __construct(protected int $start, protected int $end) {

	}

	public function contains(int|self $value): bool {
		if($value instanceof static) {
			return $this->contains($value->getStart()) || $this->contains($value->getEnd()) ||
				$value->contains($this->getStart()) || $value->contains($this->getEnd());
		} else {
			return $this->getStart() <= $value && $value <= $this->getEnd();
		}
	}

	public function merge(self $range): static|false {
		if(false === $this->contains($range)) {
			return false;
		}
		return new static(
			min($this->getStart(), $range->getStart()),
			max($this->getEnd(), $range->getEnd())
		);
	}

	public function getStart(): int {
		return $this->start;
	}

	public function getEnd(): int {
		return $this->end;
	}
}