<?php

namespace App\Services\Exchanges;

use App\DTO\MarkerRateDTO;
use Exception;
use Illuminate\Support\Facades\Http;

abstract class BaseExchangeClient
{
    public const EXCHANGE = '';
    protected string $host;
    protected array $pairRatioList = [];
    abstract public function getPairRatioList(bool $forceRefresh = false): array;

    final public function __construct()
    {
    }

    public static function create(): static
    {
        return new static();
    }

    /**
     * @param string $pair
     * @return MarkerRateDTO
     * @throws Exception
     */
    abstract public function getPairRatioItem(string $pair): MarkerRateDTO;
    public function send(string $path, array $params = [], string $method = 'GET'): array
    {
        $response = Http::$method($this->host.$path, $params);
        return json_decode($response?->body(), true) ?? [];
    }

    protected function formatPair(string $pair): string
    {
        return strtolower(preg_replace('/[\s_\/\.+\\\\]+/', '', $pair));
    }
}
