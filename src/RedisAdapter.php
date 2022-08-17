<?php

namespace App;

use Redis;
use RedisException;

class RedisAdapter implements RedisAdapterInterface
{
    protected Redis $redis;
    protected const MINUTE = 60;


    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379);
    }

    public function retrieveData(string $key): ?string
    {
        $result = $this->redis->get($key);

        if ($result !== false) {

            return $result;
        }

        return null;
    }

    public function storeData(string $key, string $value): void
    {
        $this->redis->set($key, $value, self::MINUTE);
    }

    public function removeData(string $key): void
    {
        $this->redis->del($key);
    }

}