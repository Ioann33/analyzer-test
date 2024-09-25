<?php

namespace App\DTO;

/**
 * @property string $exchange
 * @property string $pair
 * @property float $rate
 */
class MarkerRateDTO extends BaseDTO
{
    public function exchange(): ?string
    {
        return $this->source['exchange'] ?? null;
    }

    public function pair(): ?string
    {
        return $this->source['pair'] ?? null;
    }

    public function rate(): ?float
    {
        return (float) $this->source['rate'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'exchange' => $this->exchange(),
            'pair' => $this->pair(),
            'rate' => $this->rate()
        ];
    }
}
