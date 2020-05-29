<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\DTO;

use Cblink\DTO\Exceptions\DTOException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use Overtrue\Validation\Factory;
use Overtrue\Validation\Translator;

abstract class DTO
{
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
     *
     * @throws \Throwable
     */
    public function __construct(array $data = [])
    {
        $this->origin = $data;
        $this->validate();
        $this->setPayload();
    }

    abstract public function rules(): array;

    /**
     * @throws \Throwable
     */
    protected function setPayload()
    {
        array_walk($this->origin, function ($val, $key) {
            // 如果key值不存在，则跳过
            if (!in_array($key, $this->fillable())) {
                return;
            }

            $method = sprintf('set%s', ucfirst(Str::snake($key)));

            // 如果存在set方法，优先使用方法赋值
            $this->payload[$key] = method_exists($this, $method) ?
                call_user_func_array([$this, $method], [$val]) : $val;
        });
    }

    /**
     * @throws \Throwable
     */
    protected function validate()
    {
        if (!$this->rules()) {
            return;
        }

        foreach (['beforeValidate', 'baseValidate', 'afterValidate'] as $validateMethod) {
            if (!method_exists($this, $validateMethod)) {
                continue;
            }

            call_user_func([$this, $validateMethod], $this->origin);
        }
    }

    /**
     * @param $origin
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    protected function baseValidate($origin)
    {
        $validator = $this->getValidator()->make($origin, $this->rules());

        if ($validator->fails()) {
           throw new DTOException($validator->errors()->first());
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Validation\Factory|mixed|Factory
     */
    protected function getValidator()
    {
        if (function_exists('app') && app() instanceof Container) {
            return app(\Illuminate\Contracts\Validation\Factory::class);
        }

        return new Factory(new Translator());
    }

    /**
     * @return array
     */
    protected function fillable()
    {
        return $this->fillable ?: array_keys($this->rules());
    }

    /**
     * @param $name
     *
     * @return array|\ArrayAccess|mixed
     *
     * @throws \Throwable
     */
    public function __get($name)
    {
        if (method_exists($this, $method = sprintf('get%s', ucfirst($name)))) {
            return call_user_func([$this, $method]);
        }

        throw_if(!array_key_exists($name, $this->payload), new DTOException(sprintf('%s attribute is not defined', $name)));

        return $this->payload[$name];
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
