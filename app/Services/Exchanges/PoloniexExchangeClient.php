<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class PoloniexExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'poloniex';
    protected string $host = 'https://api.poloniex.com';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/markets/price');
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
                    'pair' => str_replace('_', '', $item['symbol']),
                    'rate' => $item['price']
                ]);
            }
        }
        throw new Exception('Exchange: '.static::EXCHANGE.' does not support pair: '.$pair);
    }
}
