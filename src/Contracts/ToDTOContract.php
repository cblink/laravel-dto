<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <i@httpd.cc>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\DTO\Contracts;

use Cblink\DTO\DTO;

/**
 * Interface ToDTOConTract.
 */
interface ToDTOContract
{
    public function toDTO(): DTO;
}
