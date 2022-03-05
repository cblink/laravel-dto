<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Tests;

use Cblink\DTO\DTO;
use Cblink\DTO\Exceptions\DTOException;
use Cblink\DTO\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class DTOTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ServiceProvider::class);
    }

    public function testCreateNewDTOFile()
    {
        $this->artisan('make:dto', [
            'name' => 'TestDTO',
        ]);

        $this->assertFileExists($filepath = app_path('DTO/TestDTO.php'));

        $this->app->make(Filesystem::class)->deleteDirectory(app_path('DTO'), true);

        $this->assertFileNotExists($filepath);
    }

    public function testCreateNewDTOFileWithPath()
    {
        $this->artisan('make:dto', [
            'name' => 'TestDTO',
            '--path' => 'Domain/DTO',
        ]);

        $this->assertFileExists($filepath = base_path('Domain/DTO/TestDTO.php'));

        $this->app->make(Filesystem::class)->deleteDirectory(base_path('Domain'), true);

        $this->assertFileNotExists($filepath);
    }

    public function testBaseDTO()
    {
        $test = 'hello dto';

        $baseDTO = new BaseDTO([
            'test' => $test,
            'user' => ['name' => 'test'],
        ]);

        $this->assertInstanceOf(DTO::class, $baseDTO);

        $this->assertInstanceOf(UserDTO::class, $baseDTO->user);

        $this->assertSame($baseDTO->test, $test);

        $this->assertFalse(empty($baseDTO->test));
    }

    public function testBaseDTOException()
    {
        $this->expectException(DTOException::class);

        $dto = new BaseDTO();
    }

    public function testGetNull()
    {
        $baseDTO = new BaseDTO([
            'test' => 'hellp',
            'user' => ['name' => 'test'],
        ]);

        $this->assertNull($baseDTO->url);

        $this->assertIsString($baseDTO->test);
    }

    public function testGetAll()
    {
        $baseDTO = new BaseDTO([
            'test' => 'hellp',
            'user' => ['name' => 'test'],
        ]);

        $this->assertIsArray($baseDTO->toArray());
    }
}

/**
 * Class BaseDTO.
 *
 * @property string  $test
 * @property UserDTO $user
 * @property string  $url
 */
class BaseDTO extends DTO
{
    public function rules(): array
    {
        return [
            'test' => ['required'],
            'user' => ['required', 'array'],
            'url' => ['nullable', 'string'],
        ];
    }

    public function setUserAttribute($user)
    {
        return new UserDTO($user);
    }
}

/**
 * Class UserDTO.
 *
 * @property int $user_d
 */
class UserDTO extends DTO
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }
}
