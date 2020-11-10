<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\DTO;

use Cblink\DTO\Traits\PayloadTrait;
use Cblink\DTO\Traits\ValidatorTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Class DTO.
 */
abstract class DTO implements Arrayable
{
    use ValidatorTrait;
    use PayloadTrait;

    /**
     * @var array
     */
    protected $origin = [];

    /**
     * DTO constructor.
     *
     * @param bool $verify
     *
     * @throws \Throwable
     */
    public function __construct(array $data = [], $verify = true)
    {
        $this->origin = $data;
        $this->verify = $verify;
        $this->bootstrap();
    }

    /**
     * @throws \Throwable
     */
    public function bootstrap()
    {
        $this->validate();
        $this->setPayload();
    }

    abstract public function rules(): array;

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getOrigin()
    {
        return $this->origin;
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
        return Arr::get($this->payload ?: $this->origin, $key, $default);
    }

    /**
     * @param $name
     *
     * @return array|\ArrayAccess|null
     *
     * @throws \Throwable
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * 实现__isset防止empty检测不到值
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->payload[$name]);
    }
}
