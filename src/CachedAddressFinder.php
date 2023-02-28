<?php

namespace CyberDuck\AddressFinder;

use CyberDuck\AddressFinder\Drivers\DriverContract;
use Illuminate\Support\Str;

/**
 * Class CachedAddressFinder
 *
 * @package CyberDuck\AddressFinder
 */
class CachedAddressFinder extends AddressFinder
{
    /**
     * @var string
     */
    private $store;

    public function __construct()
    {
        $this->store = config('laravel-address-finder.cache.store', 1440);
    }

    /**
     * @param $query
     * @param $country
     * @param $group_id
     * @return Suggestions
     */
    public function suggestions($query, $country, $group_id)
    {
        return \Cache::store($this->store)->remember(
            $this->buildCacheKey([$query, $country, $group_id]),
            config('laravel-address-finder.cache.ttl', 1440),
            function () use ($query, $country, $group_id) {
                return parent::suggestions($query, $country, $group_id);
            }
        );
    }

    /**
     * @param $addressId
     * @return Details
     */
    public function details($addressId, bool $raw = false)
    {
        $cacheKeyArr = $raw ? [$addressId, 'raw'] : [$addressId];

        return \Cache::store($this->store)->remember(
            $this->buildCacheKey($cacheKeyArr),
            config('laravel-address-finder.cache.ttl', 1440),
            function () use ($addressId, $raw) {
                return parent::details($addressId, $raw);
            }
        );
    }

    /**
     * @param $slug
     * @return string
     */
    private static function makeSlug($slug): string
    {
        return Str::of($slug)->slug('-');
    }

    /**
     * @param $params
     * @return string
     */
    private function buildCacheKey($params)
    {
        return implode('-', array_map('self::makeSlug', $params));
    }
}
