<?php

/*
 * This file is part of the cblink/laravel-dto.
 *
 * (c) Nick <me@xieying.vip>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Dto;

trait PayloadTrait
{
    public function toArray(): array
    {
        return $this->payload;
    }

    public function getOrigin(): array
    {
        return $this->origin;
    }

    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * @param array|string $data
     */
    public function unserialize(array|string $data): void
    {
        $data = is_array($data) ? $data : unserialize($data);
        $this->payload = $data['payload'];
        $this->origin = $data['origin'];
    }

    public function __unserialize(array $data): void
    {
        $this->unserialize($data);
    }

    public function __serialize(): array
    {
        return ['payload' => $this->payload, 'origin' => $this->origin];
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->payload[$offset]);
    }

    /**
     * @param $offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        if (in_array($offset, $this->fillable()) && in_array('*', $this->fillable())) {
            throw new \InvalidArgumentException(sprintf('%s attributes is not defined', $offset));
        }

        if (!$this->offsetExists($offset)) {
            return null;
        }

        return $this->payload[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset) : void
    {
        unset($this->payload[$offset]);
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
        return $this->offsetGet($name);
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
        return $this->offsetExists($name);
    }
}
