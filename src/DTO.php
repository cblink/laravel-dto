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
        $this->setOriginData($data);
        $this->bootstrap($verify);
    }

    /**
     * @param $verify
     *
     * @throws \Throwable
     */
    public function bootstrap($verify)
    {
        $this->validate($verify);
        $this->setPayload();
    }

    abstract public function rules(): array;

    public function toArray(): array
    {
        return $this->payload;
    }

    public function getOrigin(): array
    {
        return $this->origin;
    }

    /**
     * @return void
     */
    public function setOriginData(array $data = [])
    {
        if (!$data && function_exists('request')) {
            $data = request()->all();
        }

        $this->origin = $data;
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
