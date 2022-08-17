<?php

namespace App;

interface CacheAdapterInterface
{
    public function get(string $key): ?array;

    public function store(string $key, array $value): bool;

    public function remove(string $key): bool;
}