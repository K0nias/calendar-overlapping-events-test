<?php declare(strict_types = 1);

namespace Calendar;

final class Event
{

    /** @var \DateTimeImmutable */
    private $start;

    /** @var \DateTimeImmutable */
    private $end;

    public function __construct(\DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        if ($end < $start) {
            throw new \InvalidArgumentException('Start date cannot be greater than end date.');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }

}
