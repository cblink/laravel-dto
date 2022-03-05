<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\DTO\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class DTOMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name : The name of the DTO}
        {--path= : The location where the migration file should be created}';

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

        return dirname(dirname(__DIR__)).'/stubs/dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->option('path')) {
            return $rootNamespace;
        }

        return $rootNamespace.'\DTO';
    }

    protected function getPath($name)
    {
        if ($path = $this->option('path')) {
            $name = Str::replaceFirst($this->rootNamespace(), '', $name);
            $filename = str_replace('\\', '/', $name);

            $path = trim($path, '/');

            return sprintf('%s/%s/%s.php',
                $this->laravel['path.base'],
                $path,
                $filename
            );
        }

        return parent::getPath($name);
    }
}
