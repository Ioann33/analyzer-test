<?php

namespace App\Services\Exchanges;

class BinanceExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'binance';
    protected string $host = 'https://api.binance.com';
    public function getPairRatioList(): array
    {
        return $this->send('/api/v3/ticker/price');
    }

    public function getPairRatioItem(string $pair): array
    {
       $pairs = $this->getPairRatioList();
       foreach ($pairs as $pair) {

       }
       return [];
    }
}
