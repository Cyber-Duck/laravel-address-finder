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
     * @param bool $raw
     * @return Suggestions|array
     */
    public function suggestions($query, $country, $group_id, bool $raw = false)
    {
        return $this->addressEngine()->suggestions($query, $country, $group_id, $raw);
    }

    /**
     * @param $addressId
     * @param bool $raw
     * @param bool $translated
     * @param array $customFields
     * @return Details|array
     */
    public function details($addressId, bool $raw = false, bool $translated = false, array $customFields = [])
    {
        return $this->addressEngine()->getDetails($addressId, $raw, $translated, $customFields);
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
