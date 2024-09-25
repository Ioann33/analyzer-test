<?php

namespace App\Services\Exchanges;

class WhiteBitExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'white-bit';
    protected string $host = 'https://whitebit.com';
    public function getPairRatioList(): array
    {
        return $this->send('/api/v4/public/ticker');
    }
    public function getPairRatioItem(string $pair): array
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $pair) {

        }
        return [];
    }
}
