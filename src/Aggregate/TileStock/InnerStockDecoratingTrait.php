<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

use Faecie\Dominos\ValueObject\Tile;

trait InnerStockDecoratingTrait
{
    private TileStock $innerStock;

    public function hasTile(int $value = null): bool
    {
        return $this->innerStock->hasTile($value);
    }

    public function pickTile(int $value = null): ?Tile
    {
        return $this->innerStock->pickTile($value);
    }

    public function putTile(Tile $tile): void
    {
        $this->innerStock->putTile($tile);
    }

    public function count(): int
    {
        return $this->innerStock->count();
    }
}
