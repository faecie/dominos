<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

use Faecie\Dominos\ValueObject\Tile;

class RandomizedStock implements TileStock
{
    /**
     * @var Tile[]
     */
    private array $tiles = [];

    public function hasTile(int $value = null): bool
    {
        foreach ($this->tiles as $tile) {
            if ($this->isMatchingTile($tile, $value)) {
                return true;
            }
        }

        return false;
    }

    public function pickTile(int $value = null): ?Tile
    {
        if ($value === null) {
            return $this->pickRandomTile();
        }

        return $this->pickRandomTileWithValue($value);
    }

    public function putTile(Tile $tile): void
    {
        $this->tiles[] = $tile;
    }

    public function count(): int
    {
        return count($this->tiles);
    }

    private function isMatchingTile(Tile $tile, int $value = null): bool
    {
        return $value === null || in_array($value, [$tile->getLeftSide(), $tile->getRightSide()], true);
    }

    private function pickRandomTileWithValue(int $value): ?Tile
    {
        $result = null;
        $updatedStock = [];
        while (count($this->tiles) > 0) {
            $current = $this->pickRandomTile();
            if ($result === null && $current instanceof Tile && $this->isMatchingTile($current, $value)) {
                $result = $current;
            } else {
                $updatedStock[] = $current;
            }
        }

        $this->tiles = $updatedStock;

        return $result;
    }

    private function pickRandomTile(): ?Tile
    {
        if ($this->count() === 0) {
            $result = null;
        } else {
            $targetKey = array_rand($this->tiles);
            $result = $this->tiles[$targetKey];
            unset($this->tiles[$targetKey]);
        }

        return $result;
    }
}
