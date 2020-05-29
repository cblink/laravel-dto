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

class DTOTest extends TestCase
{
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
}

/**
 * Class BaseDTO.
 *
 * @property string  $test
 * @property UserDTO $user
 */
class BaseDTO extends DTO
{
    public function rules(): array
    {
        return [
            'test' => ['required'],
            'user' => ['required', 'array'],
        ];
    }

    public function setUser($user)
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
