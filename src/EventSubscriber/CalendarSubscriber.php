<?php

namespace App\EventSubscriber;

use CalendarBundle\CalendarEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}