<?php

namespace App\Cache;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Address
{
    protected PendingRequest $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::baseUrl(config('services.api_url.address'));
    }

    /**
     * @return array
     * @throws ConnectionException
     */
    public function provinces(): array
    {
        $key = 'provinces';

        if (!Cache::has($key)) {
            $body = $this->httpClient->get('1/0.htm')->json();

            Cache::set($key, $body['data'], config('services.api_time.cache'));
        }

        return Cache::get($key);
    }

    /**
     * @param string $province_id
     * @return array
     * @throws ConnectionException
     */
    public function districts(string $province_id): array
    {
        $key = "districts_$province_id";

        if (!Cache::has($key)) {
            $body = $this->httpClient->get("2/$province_id.htm")->json();

            Cache::set($key, $body['data'], config('services.api_time.cache'));
        }

        return Cache::get($key);
    }

    /**
     * @param string $district_id
     * @return array|null
     * @throws ConnectionException
     */
    public function wards(string $district_id): ?array
    {
        $key = "wards_$district_id";

        if (!Cache::has($key)) {
            $body = $this->httpClient->get("3/$district_id.htm")->json();

            Cache::set($key, $body['data'], config('services.api_time.cache'));
        }

        return Cache::get($key);
    }
}
