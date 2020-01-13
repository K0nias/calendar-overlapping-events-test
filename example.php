<?php

use Calendar\Event;
use Calendar\EventOverlapsResolver;

require_once __DIR__ . '/vendor/autoload.php';

$testingData = [
    // 1.1.10:00 - 2.1.10:00
    //                        3.1.10:00 - 4.1.10:00
    [
        new Event(new DateTimeImmutable('2020-01-01 10:00:00'), new DateTimeImmutable('2020-01-02 10:00:00')),
        new Event(new DateTimeImmutable('2020-01-03 10:00:00'), new DateTimeImmutable('2020-01-04 10:00:00')),
    ],
    // 1.1.10:00 - 2.1.10:00
    //                  2.1.9:00 - 4.1.10:00
    [
        new Event(new DateTimeImmutable('2020-01-01 10:00:00'), new DateTimeImmutable('2020-01-02 10:00:00')),
        new Event(new DateTimeImmutable('2020-01-02 09:00:00'), new DateTimeImmutable('2020-01-04 10:00:00')),
    ],
    // 1.1.10:00          -            2.1.10:00
    //                                             3.1.10:00 - 4.1.10:00
    //                    2.1.8:00 - 2.1.9:00
    //       1.1.15:00         -       2.1.10:00
    //                                             4.1.9:00         -       5.1.10:00
    [
        new Event(new DateTimeImmutable('2020-01-01 10:00:00'), new DateTimeImmutable('2020-01-02 10:00:00')),
        new Event(new DateTimeImmutable('2020-01-03 10:00:00'), new DateTimeImmutable('2020-01-04 10:00:00')),
        new Event(new DateTimeImmutable('2020-01-02 08:00:00'), new DateTimeImmutable('2020-01-02 09:00:00')),
        new Event(new DateTimeImmutable('2020-01-01 15:00:00'), new DateTimeImmutable('2020-01-02 10:00:00')),
        new Event(new DateTimeImmutable('2020-01-04 09:00:00'), new DateTimeImmutable('2020-01-05 10:00:00')),
    ],
];

$overlapsResolver = new EventOverlapsResolver();

foreach ($testingData as $testData) {
    print_r($overlapsResolver->computeOverlappingEvents($testData));
}
