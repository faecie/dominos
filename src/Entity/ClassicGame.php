<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

use Faecie\Dominos\Aggregate\PlayersQueue;
use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\ObserverSubjectTrait;
use Faecie\Dominos\ValueObject\Tile;

class ClassicGame implements DominoGame
{
    use ObserverSubjectTrait;

    /**
     * @var PlayersQueue|DominoPlayer[] Players playing this game
     */
    private $players;

    private TileStock $stock;
    private Board $board;
    private int $status = GameStatusesEnum::STARTED;
    private ?DominoPlayer $player = null;

    public function __construct(PlayersQueue $players, TileStock $stock, Board $board)
    {
        $this->players = $players;
        $this->stock = $stock;
        $this->board = $board;
    }

    public function getExtraTile(): ?Tile
    {
        return $this->stock->pickTile();
    }

    public function hasExtraTile(): bool
    {
        return $this->stock->hasTile();
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function play(): void
    {
        if ($this->getStatus() === GameStatusesEnum::STARTED) {
            $this->notify();
        }

        if ($this->hasEnded()) {
            $this->status = GameStatusesEnum::ENDED;
            $this->notify();

            return;
        }

        $this->player = $this->players->nextPlayer();
        if (!$this->getCurrentPlayer() instanceof DominoPlayer) {
            return;
        }

        if ($this->getCurrentPlayer()->play($this) === PlayerStatusesEnum::ADDED_TILE) {
            $this->status = GameStatusesEnum::PLAYED;
            $this->notify();
        }
    }

    public function getCurrentPlayer(): ?DominoPlayer
    {
        return $this->player;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getWinners(): array
    {
        $result = [];
        foreach ($this->players as $player) {
            if ($player->getStatus() === PlayerStatusesEnum::WINNER) {
                $result[] = $player;
            }
        }

        return $result;
    }

    public function hasEnded(): bool
    {
        $result = true;
        foreach ($this->players as $player) {
            if ($player->getStatus() === PlayerStatusesEnum::WINNER) {
                return true;
            }

            if ($player->getStatus() !== PlayerStatusesEnum::CANT_PLAY) {
                $result = false;
            }
        }

        return $result;
    }
}
