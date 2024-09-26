<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class ExmoExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'exmo';
    protected string $host = 'https://api.exmo.me';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/v1.1/ticker');
        }
        return $this->pairRatioList;
    }
    public function getPairRatioItem(string $pair): MarkerRateDTO
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $key => $item) {
            if ($this->formatPair($key) === $this->formatPair($pair)) {
                return MarkerRateDTO::create([
                    'exchange' => ucfirst(static::EXCHANGE),
                    'pair' => str_replace('_', '', $key),
                    'rate' => $item['last_trade']
                ]);
            }
        }
        throw new Exception('Exchange: '.static::EXCHANGE.' does not support pair: '.$pair);
    }
}
