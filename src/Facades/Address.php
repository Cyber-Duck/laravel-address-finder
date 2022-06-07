<?php

namespace CyberDuck\AddressFinder\Facades;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Suggestions suggestions($query, $country, $group_id)
 * @method static Details details($id, $raw = false)
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
