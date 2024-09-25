<?php

namespace App\Services\Exchanges;

class PoloniexExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'poloniex';
    protected string $host = 'https://api.poloniex.com';
    public function getPairRatioList(): array
    {
        return $this->send('/markets/price');
    }
    public function getPairRatioItem(string $pair): array
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $pair) {

        }
        return [];
    }
}
