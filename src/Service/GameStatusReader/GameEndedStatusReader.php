<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\GameStatusesEnum;

class GameEndedStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        $winners = array_map(fn(DominoPlayer $player) => $player->getName(), $objectWithStatus->getWinners());

        if (empty($winners)) {
            return 'Spare. Game has ended. No winners.';
        }

        return sprintf('Player %s has won', implode(',', $winners));
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        return $objectWithStatus instanceof DominoGame && $objectWithStatus->getStatus() === GameStatusesEnum::ENDED;
    }
}
