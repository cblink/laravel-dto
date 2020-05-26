<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class DTOMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DTO class';

    protected function getStub()
    {
        if (file_exists(base_path('stubs/dto.stub'))) {
            return base_path('stubs/dto.stub');
        }

        return dirname(dirname(__DIR__)) . '/stubs/dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DTO';
    }
}
