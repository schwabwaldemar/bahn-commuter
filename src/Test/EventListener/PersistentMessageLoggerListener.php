<?php

declare(strict_types=1);

namespace App\Test\EventListener;

use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\MessageEvents;
use Symfony\Component\Mailer\EventListener\MessageLoggerListener;

/**
 * Keeps sent mail events available across kernel reboots during functional tests.
 */
final class PersistentMessageLoggerListener extends MessageLoggerListener
{
    private static ?MessageEvents $persistedEvents = null;

    public function __construct()
    {
        parent::__construct();

        if (null !== self::$persistedEvents) {
            foreach (self::$persistedEvents->getEvents() as $event) {
                $this->onMessage($event);
            }

            self::$persistedEvents = null;
        }
    }

    public function reset(): void
    {
        self::$persistedEvents = $this->cloneEvents($this->getEvents());

        parent::reset();
    }

    private function cloneEvents(MessageEvents $events): MessageEvents
    {
        $cloned = new MessageEvents();

        foreach ($events->getEvents() as $event) {
            $cloned->add($this->cloneEvent($event));
        }

        return $cloned;
    }

    private function cloneEvent(MessageEvent $event): MessageEvent
    {
        return new MessageEvent($event->getMessage(), $event->getEnvelope(), $event->getTransport(), $event->isQueued());
    }
}
