<?php

namespace App;

use Exception;
use SimpleXMLElement;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CurrencyRateService
{
    protected CacheAdapterInterface $cache;

    /**
     * @param CacheAdapterInterface $cache
     */
    public function __construct(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function queryCurrencyRates(): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
        return $response->getContent();
    }

    /**
     * @throws Exception
     */
    protected function parseCurrencyRates(string $currencyRatesRaw): array
    {
        $xml = new SimpleXMLElement($currencyRatesRaw);

        $currencyRates = [];

        foreach ($xml->Cube->Cube->Cube as $c) {
            $currency = (string)$c['currency'];
            $rate = (float)$c['rate'];
            $currencyRates[$currency] = $rate;
        }

        return $currencyRates;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function getCurrencyRates(): array
    {
        $cacheKey = 'currency-rates';
        $cachedValue = $this->cache->get($cacheKey);

        if ($cachedValue) {
            return $cachedValue;
        }

        $currencyRatesRaw = $this->queryCurrencyRates();
        $currencyRates = $this->parseCurrencyRates($currencyRatesRaw);
        $this->cache->store($cacheKey, $currencyRates);

        return $currencyRates;
    }
}