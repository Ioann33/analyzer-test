<?php

namespace App\Console\Commands;

use App\Handlers\RatioAnalysisHandler;
use App\Services\Exchanges\BinanceExchangeClient;
use Exception;
use Illuminate\Console\Command;

class ProfitAnalysisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:profit-analysis-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Profit analysis';

    protected array $pairList = [
        'BTCUSDT',
        'ETHUSDT',
        'XRPUSDT',
        'SHIBUSDT',
        'SUSHIUSDT',
        'AVAXUSDT',
        'DOTUSDT',
        'DOGEUSDT',
        'TRXUSDT',
        'FILUSDT',
        'UNIUSDT',
        'SUIUSDT',
        'NEARUSDT',
        'SSVUSDT',
        'ENSUSDT',
        'OPUSDT',
        'LTCUSDT',
        'ADAUSDT',
        'NEOUSDT',
        'ETCUSDT',
        'LINKUSDT',
        'DASHUSDT',
        'ATOMUSDT',
    ];

    /**
     * Execute the console command.
     */
    public function handle(RatioAnalysisHandler $handler, BinanceExchangeClient $client): void
    {
        $pairList = $client->getPairRatioList();
        $pairList = array_map(function($item) {
            return $item['symbol'];
        }, $pairList);
        $pairList = array_filter($pairList, function($item) {
            return str_ends_with($item, 'USDT');
        });
        try {
            $this->table(
                ['pair', 'buy', 'sell', 'profit'],
                $handler->getPairMargins($pairList)
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
