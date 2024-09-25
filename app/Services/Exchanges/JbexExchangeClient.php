<?php

namespace App\Services\Exchanges;

class JbexExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'jbex';
    protected string $host = 'https://api.jbex.com';
    public function getPairRatioList(): array
    {
        return $this->send('/openapi/quote/v1/ticker/price');
    }
    public function getPairRatioItem(string $pair): array
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $pair) {

        }
        return [];
    }
}
