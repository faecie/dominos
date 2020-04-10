<?php

declare(strict_types=1);

namespace Faecie\Dominos\Exception;

use Faecie\Dominos\ValueObject\Tile;
use RuntimeException;

class TileMisplacementException extends RuntimeException
{
    public function __construct(Tile $tile)
    {
        parent::__construct(sprintf(
            'Failed to place a tile %s on the board. A tile can only be placed next to an adjacent value',
            $tile->toString()
        ));
    }
}
