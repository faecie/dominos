<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

interface WithStatus
{
    /**
     * Returns a status of an object
     */
    public function getStatus(): int;
}
