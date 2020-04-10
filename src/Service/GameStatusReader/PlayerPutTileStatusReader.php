<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Enum\PlayerStatusesEnum;

class PlayerPutTileStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        return sprintf('%s plays %s to connect to tile %s on the board',
            $objectWithStatus->getCurrentPlayer()->getName(),
            $objectWithStatus->getCurrentPlayer()->getLastTile()->toString(),
            $objectWithStatus->getBoard()->getAdjacentTile()->toString(),
        );
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        if (!$objectWithStatus instanceof DominoGame || $objectWithStatus->getStatus() !== GameStatusesEnum::PLAYED) {
            return false;
        }

        $player = $objectWithStatus->getCurrentPlayer();

        return $player instanceof DominoPlayer && $player->getStatus() === PlayerStatusesEnum::ADDED_TILE;
    }
}
