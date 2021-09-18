<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Tests;

use Cblink\Dto\Dto;

class DtoTest extends TestCase
{
    public function testBaseDTO()
    {
        $test = 'hello dto';

        $baseDTO = new BaseDTO([
            'test' => $test,
            'user' => ['name' => 'test'],
        ]);

        $this->assertInstanceOf(Dto::class, $baseDTO);

        $this->assertSame($baseDTO->test, $test);

        $this->assertFalse(empty($baseDTO->test));
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
 * @property array $user
 * @property string  $url
 */
class BaseDTO extends DTO
{
    protected $fillable = ['test', 'user', 'url'];
}

/**
 * Class UserDTO.
 *
 * @property string $name
 */
class UserDTO extends DTO
{
    protected $fillable = ['name'];
}
