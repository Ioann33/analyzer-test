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

    protected array $clientInstances = [

    ];


    /**
     * @var array<int, MarkerRateDTO>
     */
    protected array $crossExchangeRatioData = [];


    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    public function getSingletonInstance(string $class)
    {
        if (!isset($this->clientInstances[$class::EXCHANGE])) {
            $this->clientInstances[$class::EXCHANGE] = app($class);
        }
        return $this->clientInstances[$class::EXCHANGE];
    }
    /**
     * @throws Exception
     */
    protected function extractRatioData(string $pair): void
    {
        foreach ($this->clients as $client){
            $clientInstance = $this->getSingletonInstance($client);
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

    /**
     * @throws Exception
     */
    public function getPairMargins(array $pairs): array
    {
        $data = [];
        foreach ($pairs as $pair) {
            $this->extractRatioData($pair);
            $data[$pair] = $this->calculateRatioExtremes();
            $this->crossExchangeRatioData = [];
        }
        return $this->mapPairMarginData($data);
    }

    /**
     * @param array<string, array<string, MarkerRateDTO>> $pairs
     * @return array<string, string>
     */
    protected function mapPairMarginData(array $pairs): array
    {
        $result = [];
        foreach ($pairs as $key => $rationExtremes) {
            $result[] = [
                'pair' => $key,
                'buy' => $rationExtremes['min']->exchange . ' ('.$rationExtremes['min']->rate.') ',
                'sell' => $rationExtremes['max']->exchange . ' ('.$rationExtremes['max']->rate.') ',
                'profit' => $this->calculateMargin($rationExtremes['min']->rate, $rationExtremes['max']->rate).'%'
            ];
        }
        return $result;
    }

    protected function calculateMargin(float $minRate, float $maxRate): string
    {
        return number_format((($maxRate - $minRate) / $minRate) * 100, 2);
    }
}
