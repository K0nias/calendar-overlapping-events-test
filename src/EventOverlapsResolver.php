<?php declare(strict_types = 1);

namespace Calendar;

final class EventOverlapsResolver
{

    /**
     * @param \Calendar\Event[] $events
     * @return \Calendar\OverlappingEvents[]
     */
    public function computeOverlappingEvents(array $events): array
    {
        $minuteMap = $this->createMinuteMap($events);

        $overlappingEvents = $this->removeNotOverlappingMinutesFromMap($minuteMap);

        ksort($overlappingEvents);

        $pairs = [];

        foreach ($overlappingEvents as $overlappingEventsPair) {
            $pairHash = $this->createOverlappingEventsPairHash($overlappingEventsPair);

            if ( ! isset($pairs[$pairHash])) {
                $pairs[$pairHash] = $overlappingEventsPair;
            }
        }

        return array_map(
            function (array $events): OverlappingEvents {
                return new OverlappingEvents(... $events);
            },
            array_values($pairs)
        );
    }

    /**
     * @param \Calendar\Event[] $overlappingEventsPair
     */
    private function createOverlappingEventsPairHash(array $overlappingEventsPair): string
    {
        $hash = '';

        foreach ($overlappingEventsPair as $event) {
            $hash .= spl_object_hash($event);
        }

        return md5($hash);
    }

    /**
     * @param \Calendar\Event[] $events
     */
    private function createMinuteMap(array $events): array
    {
        $minuteMap = [];

        foreach ($events as $event) {
            $startDate = $event->getStart();
            $endDate = $event->getEnd();

            while ($startDate <= $endDate) {
                $utcMinute = $this->getUtcMinute($startDate);
                if ( ! isset($minuteMap[$utcMinute])) {
                    $minuteMap[$utcMinute] = [];
                }

                $minuteMap[$utcMinute][] = $event;

                $startDate = $startDate->modify('+1 minute');
            }
        }

        return $minuteMap;
    }

    /**
     * @param array<int, \Calendar\Event[]> $minuteMap
     * @return array<int, \Calendar\Event[]>
     */
    private function removeNotOverlappingMinutesFromMap(array $minuteMap): array
    {
        return array_filter(
            $minuteMap,
            function (array $events): bool {
                return 1 < count($events);
            }
        );
    }

    private function getUtcMinute(\DateTimeImmutable $date): int
    {
        $utcDate = $date->setTimezone(new \DateTimeZone('UTC'));

        return $utcDate->getTimestamp();
    }

}
