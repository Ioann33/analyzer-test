<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class BinanceExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'binance';
    protected string $host = 'https://api.binance.com';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/api/v3/ticker/price');
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
