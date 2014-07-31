<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Batch\Task\Subscriber;

use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\PackshotErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\PackshotEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackEvent;
use Kompakt\Mediameister\Batch\Task\Subscriber\Share\Summary;

class GenericSummaryMaker implements EventSubscriberInterface
{
    const OK = 'ok';
    const ERROR = 'error';

    protected $eventNames = null;
    protected $summary = null;

    public function __construct(
        EventNamesInterface $eventNames,
        Summary $summary
    )
    {
        $this->eventNames = $eventNames;
        $this->summary = $summary;
    }

    public function getSubscriptions()
    {
        return array(
            $this->eventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->eventNames->packshotLoadError() => array(
                array('onPackshotLoadError', 0)
            ),
            $this->eventNames->track() => array(
                array('onTrack', 0)
            ),
            $this->eventNames->trackError() => array(
                array('onTrackError', 0)
            )
        );
    }

    public function onPackshotLoad(PackshotEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getPackshotCounter()->add(self::OK, $id);
    }

    public function onPackshotLoadError(PackshotErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getPackshotCounter()->add(self::ERROR, $id);
    }

    public function onTrack(TrackEvent $event)
    {
        $id = $event->getPackshot()->getName() . spl_object_hash($event->getTrack());
        $this->summary->getTrackCounter()->add(self::OK, $id);
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $id = $event->getPackshot()->getName() . spl_object_hash($event->getTrack());
        $this->summary->getTrackCounter()->add(self::ERROR, $id);
    }
}