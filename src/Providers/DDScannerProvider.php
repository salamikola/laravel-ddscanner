<?php

namespace Salamikola\LaravelDDScanner\Providers;

use Illuminate\Support\ServiceProvider;
use Salamikola\LaravelDDScanner\Commands\DDScannerCommand;


class DDScannerProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                   DDScannerCommand::class
                ],
            );
        }

    }
}
