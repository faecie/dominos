<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\PlayerStatusesEnum;

class PlayerDrawsTileStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        return sprintf('%s cant play, drawing tile %s',
            $objectWithStatus->getName(),
            $objectWithStatus->getLastTile()->toString()
        );
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        return $objectWithStatus instanceof DominoPlayer &&
            $objectWithStatus->getStatus() === PlayerStatusesEnum::EXTRA_TILE;
    }
}
