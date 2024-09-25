<?php

namespace App\Handlers;

use App\DTO\MarkerRateDTO;
use App\Services\Exchanges\BaseExchangeClient;
use App\Services\Exchanges\BinanceExchangeClient;
use App\Services\Exchanges\ByBitExchangeClient;
use App\Services\Exchanges\JbexExchangeClient;
use App\Services\Exchanges\PoloniexExchangeClient;
use App\Services\Exchanges\WhiteBitExchangeClient;
use Exception;

/**
 * @property array<int, class-string<BaseExchangeClient>> $clients
 */
class RatioAnalysisHandler
{
    protected array $clients = [
        BinanceExchangeClient::class,
        ByBitExchangeClient::class,
        JbexExchangeClient::class,
        PoloniexExchangeClient::class,
        WhiteBitExchangeClient::class,
    ];


    /**
     * @var array<int, MarkerRateDTO>
     */
    protected array $crossExchangeRatioData = [];



    /**
     * @throws Exception
     */
    protected function extractRatioData(string $pair): void
    {
        foreach ($this->clients as $client){
            /** @var BaseExchangeClient $clientInstance */
            $clientInstance = $client::create();
            $this->crossExchangeRatioData[] = $clientInstance->getPairRatioItem($pair);
        }
    }

    /**
     * @return array<string, MarkerRateDTO>
     */
    protected function calculateRatioExtremes(): array
    {
        $minRateData = null;
        $maxRateData = null;
        foreach ($this->crossExchangeRatioData as $rateData) {
            $rate = $rateData->rate;
            if ($minRateData === null || $rate < $minRateData->rate) {
                $minRateData = $rateData;
            }
            if ($maxRateData === null || $rate > $maxRateData->rate) {
                $maxRateData = $rateData;
            }
        }
        return [
            'min' => $minRateData,
            'max' => $maxRateData
        ];
    }

    /**
     * @throws Exception
     */
    public function getRatioExtremes(string $pair): array
    {
        $this->extractRatioData($pair);
        return $this->calculateRatioExtremes();
    }
}
