<?php

namespace App\Console\Commands;

use App\Services\Exchanges\BinanceExchangeClient;
use App\Services\Exchanges\ByBitExchangeClient;
use App\Services\Exchanges\JbexExchangeClient;
use App\Services\Exchanges\PoloniexExchangeClient;
use App\Services\Exchanges\WhiteBitExchangeClient;
use Illuminate\Console\Command;

class RatioAnalysisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ratio-analysis-command {pair}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ratio analysis command';

    /**
     * Execute the console command.
     */
    public function handle(BinanceExchangeClient $client)
    {
        $pair = explode('=', $this->argument('pair'))[1];
//        print_r($client->getPairRatioList());

    }
}
