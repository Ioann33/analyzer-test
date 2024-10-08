<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class JbexExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'jbex';
    protected string $host = 'https://api.jbex.com';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/openapi/quote/v1/ticker/price');
        }
        return $this->pairRatioList;
    }
    public function getPairRatioItem(string $pair): MarkerRateDTO
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $item) {
            if ($this->formatPair($item['symbol']) === $this->formatPair($pair)) {
                return MarkerRateDTO::create([
                    'exchange' => ucfirst(static::EXCHANGE),
                    'pair' => $item['symbol'],
                    'rate' => $item['price']
                ]);
            }
        }
        throw new Exception('Exchange: '.static::EXCHANGE.' does not support pair: '.$pair);
    }
}
