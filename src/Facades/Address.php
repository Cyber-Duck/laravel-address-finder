<?php

namespace CyberDuck\AddressFinder\Facades;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Suggestions|array suggestions($query, $country, $group_id, bool $raw = false)
 * @method static Details details($id, bool $raw = false, bool $translated = false, array $customFields = [])
 *
 * @see \CyberDuck\AddressFinder\AddressFinder
 *
 * */
class Address extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'address-finder';
    }
}
