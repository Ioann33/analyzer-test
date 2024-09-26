<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;

class WhiteBitExchangeClient extends BaseExchangeClient
{
    public const EXCHANGE = 'white-bit';
    protected string $host = 'https://whitebit.com';
    public function getPairRatioList(bool $forceRefresh = false): array
    {
        if (empty($this->pairRatioList) || $forceRefresh) {
            $this->pairRatioList = $this->send('/api/v4/public/ticker');
        }
        return $this->pairRatioList;
    }
    public function getPairRatioItem(string $pair): MarkerRateDTO
    {
        $pairs = $this->getPairRatioList();
        foreach ($pairs as $key => $item) {
            if ($this->formatPair($key) === $this->formatPair($pair)) {
                return MarkerRateDTO::create([
                    'exchange' => str_replace('-','', ucfirst(static::EXCHANGE)),
                    'pair' => str_replace('_', '', $key),
                    'rate' => $item['last_price']
                ]);
            }
        }
        throw new Exception('Exchange: '.static::EXCHANGE.' does not support pair: '.$pair);
    }
}
