<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class ByBitExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'by-bit';
    protected string $host = 'https://api.bybit.com';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/v5/market/tickers', ['category' => 'spot'])['result']['list'] ?? [];
        }
        return $this->pairRatioList;
    }
    public function getPairRatioItem(string $pair): MarkerRateDTO
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $item) {
            if ($this->formatPair($item['symbol']) === $this->formatPair($pair)) {
                return MarkerRateDTO::create([
                    'exchange' => str_replace('-','', ucfirst(static::EXCHANGE)),
                    'pair' => $item['symbol'],
                    'rate' => $item['lastPrice']
                ]);
            }
        }
        throw new Exception('Exchange: '.static::EXCHANGE.' does not support pair: '.$pair);
    }
}
