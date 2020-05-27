<?php

namespace Cblink\DTO;

use App\Console\Commands\DTOMakeCommand;
use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()){
            $this->commands([
                DTOMakeCommand::class,
            ]);
        }
    }

}