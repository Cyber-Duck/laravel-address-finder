<?php

namespace CyberDuck\AddressFinder;

use CyberDuck\AddressFinder\Drivers\DriverContract;

/**
 * Class AddressFinder
 *
 * @package CyberDuck\AddressFinder
 */
class AddressFinder
{
    /**
     * @param $query
     * @param $country
     * @param $group_id
     * @return Suggestions
     */
    public function suggestions($query, $country, $group_id)
    {
        return $this->addressEngine()->suggestions($query, $country, $group_id);
    }

    /**
     * @param $addressId
     * @param bool $raw
     * @return Details|array
     */
    public function details($addressId, bool $raw = false)
    {
        return $this->addressEngine()->getDetails($addressId, $raw);
    }

    /**
     * Get the Scout engine for the model.
     *
     * @return DriverContract
     */
    public function addressEngine()
    {
        return app(DriverManager::class)->engine();
    }
}
