<?php

namespace App\Services\Exchanges;

use Exception;
use Illuminate\Support\Facades\Http;

abstract class BaseExchangeClient
{
    public const EXCHANGE = '';
    protected string $host;
    abstract public function getPairRatioList(): array;

    /**
     * @param string $pair
     * @return array
     * @throws Exception
     */
    abstract public function getPairRatioItem(string $pair): array;
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
