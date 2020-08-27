<?php

namespace CyberDuck\AddressFinder;

use CyberDuck\AddressFinder\Drivers\DriverContract;

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
    public function details($addressId)
    {
        return \Cache::store($this->store)->remember(
            $this->buildCacheKey([$addressId]),
            config('laravel-address-finder.cache.ttl', 1440),
            function () use ($addressId) {
                return parent::details($addressId);
            }
        );
    }

    /**
     * @param $params
     * @return string
     */
    private function buildCacheKey($params)
    {
        return implode('-', array_map('str_slug', $params));
    }
}
