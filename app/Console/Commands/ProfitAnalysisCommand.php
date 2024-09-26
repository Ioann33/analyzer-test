<?php

namespace App\Console\Commands;

use App\Handlers\RatioAnalysisHandler;
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
        'OPUSDT'
    ];

    /**
     * Execute the console command.
     */
    public function handle(RatioAnalysisHandler $handler): void
    {
        try {
            $this->table(
                ['pair', 'buy', 'sell', 'profit'],
                $handler->getPairMargins($this->pairList)
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
