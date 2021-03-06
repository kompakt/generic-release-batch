<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Util\Filesystem\Factory;

use Kompakt\Mediameister\Util\Filesystem\ChildFileNamer;

class ChildFileNamerFactory
{
    public function getInstance($dir)
    {
        return new ChildFileNamer($dir);
    }
}