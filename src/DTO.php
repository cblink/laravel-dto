<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Dto;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Serializable;

/**
 * Class DTO.
 */
class Dto implements Arrayable, Serializable, ArrayAccess
{
    use PayloadTrait;

    /**
     * @var array
     */
    protected $origin = [];

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * DTO constructor.
     */
    public function __construct(array $data = [])
    {
        $this->setOriginData($data)->setPayload();
    }

    /**
     * @return $this
     */
    protected function setOriginData(array $data = []): DTO
    {
        $this->origin = $data;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setPayload(array $payload = []): DTO
    {
        $payload = $payload ?: $this->getOrigin();

        $fillable = $this->fillable();

        $this->payload = in_array('*', $fillable) ?
            $payload :
            Arr::only($payload, $fillable);

        return $this;
    }

    /**
     * @return array|string[]
     */
    protected function fillable(): array
    {
        if (empty($this->fillable)) {
            $this->fillable = ['*'];
        }

        return $this->fillable;
    }

    /**
     * 获取参数，不经过.
     *
     * @param $key
     * @param null $default
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getItem($key, $default = null)
    {
        return Arr::get($this->payload, $key, $default);
    }
}
