<?php

use App\CurrencyRateService;
use App\MemcachedAdapter;
use App\RedisAdapter;

require 'vendor/autoload.php';


//$cache = new MemcachedAdapter();
//
//$crService = new CurrencyRateService($cache);
//$time = hrtime(true);
//$cr = $crService->getCurrencyRates();
//var_dump(((hrtime(true) - $time)) * 0.000001);
$cache = new RedisAdapter();
$cache->storeData('test', json_encode(['a', 'b', 'c'], JSON_THROW_ON_ERROR));
$res = $cache->retrieveData('test');
$cache->removeData('test');
var_dump(json_decode($res));