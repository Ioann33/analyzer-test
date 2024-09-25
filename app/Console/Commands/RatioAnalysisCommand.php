<?php

namespace App\Console\Commands;

use App\Handlers\RatioAnalysisHandler;
use Exception;
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
    public function handle(RatioAnalysisHandler $handler): void
    {
        $pair = explode('=', $this->argument('pair'))[1];
        try {
            $extremesRadioData = $handler->getRatioExtremes($pair);
            $this->table(
                ['exchange', 'pair', 'rate'],
                [
                    $extremesRadioData['min']->toArray(),
                    $extremesRadioData['max']->toArray()
                ]
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
