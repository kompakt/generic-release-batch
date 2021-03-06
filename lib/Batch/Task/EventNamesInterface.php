<?php

/*
 * This file is part of the kompakt/mediameister package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Mediameister\Batch\Task;

interface EventNamesInterface
{
    public function taskRun();
    public function taskRunError();
    public function taskEnd();
    public function taskEndError();
    public function packshotLoadOk();
    public function packshotLoadError();
    public function packshotUnload();
    public function packshotUnloadError();
    public function track();
    public function trackError();
}