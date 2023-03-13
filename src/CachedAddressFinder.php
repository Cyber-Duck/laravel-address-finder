<?php

namespace CyberDuck\AddressFinder;

use CyberDuck\AddressFinder\Drivers\DriverContract;
use Illuminate\Support\Arr;
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
     * @param bool $raw
     * @return Suggestions|array
     */
    public function suggestions($query, $country, $group_id, bool $raw = false): Suggestions|array
    {
        $cacheKeyArr = array_filter([
            $query,
            $country,
            $group_id,
            $raw ? 'raw' : null,
        ]);

        return \Cache::store($this->store)->remember(
            $this->buildCacheKey($cacheKeyArr),
            config('laravel-address-finder.cache.ttl', 1440),
            function () use ($query, $country, $group_id, $raw) {
                return parent::suggestions($query, $country, $group_id, $raw);
            }
        );
    }

    /**
     * @param $addressId
     * @param bool $raw
     * @param bool $translated
     * @param array $customFields
     * @return Details
     */
    public function details($addressId, bool $raw = false, bool $translated = false, array $customFields = [])
    {
        $cacheKeyArr = array_filter([
            $addressId,
            $raw ? 'raw' : null,
            $translated ? 'translated' : null,
            ! empty($customFields) ? Arr::join($customFields, '-') : null,
        ]);

        return \Cache::store($this->store)->remember(
            $this->buildCacheKey($cacheKeyArr),
            config('laravel-address-finder.cache.ttl', 1440),
            function () use ($addressId, $raw, $translated, $customFields) {
                return parent::details($addressId, $raw, $translated, $customFields);
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
    private function buildCacheKey($params): string
    {
        return implode('-', array_map('self::makeSlug', $params));
    }
}
