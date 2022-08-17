<?php

namespace App;

interface RedisAdapterInterface
{
    public function retrieveData(string $key): ?string;

    public function storeData(string $key, string $value): void;

    public function removeData(string $key): void;
}