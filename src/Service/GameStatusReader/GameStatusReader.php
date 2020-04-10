<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\WithStatus;

interface GameStatusReader
{
    /**
     * Reads a status of an object
     *
     * @param WithStatus $objectWithStatus An object to read status of
     *
     * @return string Status represented as any binary
     */
    public function read(WithStatus $objectWithStatus): string;

    /**
     * Whether this reader can read a status of a given object
     *
     * @param WithStatus $objectWithStatus An object to check
     *
     * @return bool
     */
    public function supports(WithStatus $objectWithStatus): bool;
}
