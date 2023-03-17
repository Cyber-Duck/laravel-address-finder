<?php

namespace CyberDuck\AddressFinder\Drivers;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;

interface DriverContract
{
    /**
     * @param $query
     * @param $country
     * @param $group_id
     * @param bool $raw
     * @return Suggestions|array
     */
    public function suggestions($query, $country, $group_id, bool $raw = false);

    /**
     * @param $id
     * @param bool $raw
     * @param bool $translated
     * @param array $customFields
     * @return Details
     */
    public function getDetails($id, bool $raw = false, bool $translated = false, array $customFields = []);
}
