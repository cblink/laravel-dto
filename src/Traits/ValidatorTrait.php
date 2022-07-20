<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\DTO\Traits;

use Cblink\DTO\Exceptions\DTOException;
use Illuminate\Contracts\Container\Container;
use Overtrue\Validation\Factory;
use Overtrue\Validation\Translator;

trait ValidatorTrait
{
    /**
     * 是否开启验证
     *
     * @var bool
     */
    protected $verify = true;

    /**
     * @param $verify
     */
    public function validate($verify)
    {
        if (!$verify || empty($this->rules())) {
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
    public function baseValidate($origin)
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
}
