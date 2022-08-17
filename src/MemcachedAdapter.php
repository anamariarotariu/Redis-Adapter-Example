<?php

namespace App;

use Memcached;

class MemcachedAdapter implements CacheAdapterInterface
{
    protected Memcached $mc;

    public function __construct()
    {
        $this->mc = new Memcached();
        $this->mc->addServer('mc', 11211);
    }

    public function get(string $key): ?array
    {
        $value = $this->mc->get($key);
        if ($value !== false) {
            return $value;
        }

        return null;
    }

    public function store(string $key, array $value): bool
    {
        return $this->mc->set($key, $value);
    }

    public function remove(string $key): bool
    {
        return $this->mc->delete($key);
    }
}