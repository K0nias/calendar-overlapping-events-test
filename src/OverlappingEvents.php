<?php declare(strict_types = 1);

namespace Calendar;

final class OverlappingEvents
{

    /** @var \Calendar\Event[] */
    private $events;

    public function __construct(Event $event1, Event $event2, Event ... $additionalEvents)
    {
        $this->events = array_merge([$event1, $event2], $additionalEvents);
    }

}
