<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Packshot\Metadata\Writer\Factory;

use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

interface WriterFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release);
}