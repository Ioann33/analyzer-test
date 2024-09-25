<?php

namespace App\Services\Exchanges;

class ByBitExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'by-bit';
    protected string $host = 'https://api.bybit.com';
    public function getPairRatioList(): array
    {
        return $this->send('/v5/market/tickers', ['category' => 'spot'])['result']['list'] ?? [];
    }
    public function getPairRatioItem(string $pair): array
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $pair) {

        }
        return [];
    }
}
