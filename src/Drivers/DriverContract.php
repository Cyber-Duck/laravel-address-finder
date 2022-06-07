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
     * @return Suggestions
     */
    public function suggestions($query, $country, $group_id): Suggestions;

    /**
     * @param $id
     * @param bool $raw
     * @return Details
     */
    public function getDetails($id, bool $raw = false): Details;
}
