<?php

namespace Cblink\DTO\Providers;

use App\Console\Commands\DTOMakeCommand;
use Illuminate\Support\ServiceProvider;

class DTOServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->commands([
            DTOMakeCommand::class,
        ]);
    }

}