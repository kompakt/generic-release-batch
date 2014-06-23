<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Task\Selection\Selector\Manager;

use Kompakt\Mediameister\Batch\Selection\Factory\SelectionFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Task\Selection\Selector\Manager\Exception\InvalidArgumentException;

class TaskManager
{
    protected $selectionFactory = null;
    protected $dropDir = null;

    public function __construct(
        SelectionFactory $selectionFactory,
        DropDir $dropDir
    )
    {
        $this->selectionFactory = $selectionFactory;
        $this->dropDir = $dropDir;
    }

    public function addPackshots($batchName, array $packshotNames)
    {
        $batch = $this->dropDir->getBatch($batchName);

        if (!$batch)
        {
            throw new InvalidArgumentException(sprintf('Batch does not exist: "%s"', $batchName));
        }

        $selection = $this->selectionFactory->getInstance($batch);

        foreach ($packshotNames as $packshotName)
        {
            $packshot = $batch->getPackshot($packshotName);

            if (!$packshot)
            {
                throw new InvalidArgumentException(sprintf('Packshot does not exist: "%s"', $packshotName));
            }

            $selection->addPackshot($packshot);
        }
    }

    public function removePackshots($batchName, array $packshotNames)
    {
        $batch = $this->dropDir->getBatch($batchName);

        if (!$batch)
        {
            throw new InvalidArgumentException(sprintf('Batch does not exist: "%s"', $batchName));
        }

        $selection = $this->selectionFactory->getInstance($batch);

        foreach ($packshotNames as $packshotName)
        {
            $packshot = $batch->getPackshot($packshotName);

            if ($packshot)
            {
                $selection->removePackshot($packshot);
            }
        }
    }
}