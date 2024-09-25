<?php

namespace App\Providers;

use App\Console\Commands\RatioAnalysisCommand;
use Illuminate\Support\ServiceProvider;

class RatioAnalysisProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RatioAnalysisCommand::class
            ]);
        }
    }
}
