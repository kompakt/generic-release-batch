<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Batch\Task\Subscriber;

use Kompakt\Mediameister\Batch\Task\EventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\PackshotErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TaskRunErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackErrorEvent;
use Kompakt\Mediameister\Logger\Handler\Factory\StreamHandlerFactory;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorLogger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $logger = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate(Logger $logger)
    {
        $this->handleListeners(true);
        $this->logger = $logger;
    }

    public function deactivate()
    {
        $this->handleListeners(false);
        $this->logger = null;
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->taskRunError(),
            [$this, 'onTaskRunError']
        );

        $this->dispatcher->$method(
            $this->eventNames->taskEndError(),
            [$this, 'onTaskEndError']
        );

        $this->dispatcher->$method(
            $this->eventNames->packshotLoadError(),
            [$this, 'onPackshotLoadError']
        );

        $this->dispatcher->$method(
            $this->eventNames->packshotUnloadError(),
            [$this, 'onPackshotUnloadError']
        );

        $this->dispatcher->$method(
            $this->eventNames->trackError(),
            [$this, 'onTrackError']
        );
    }

    public function onTaskRunError(TaskRunErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onPackshotLoadError(PackshotErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s: "%s"',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onPackshotUnloadError(PackshotErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s: "%s"',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s (track): "%s"',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }
}