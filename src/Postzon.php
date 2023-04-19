<?php

namespace CyberDuck\AddressFinder;

class Postzon
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param array $items
     * @return Postzon
     */
    public function setItems(array $items): Postzon
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->items;
    }
}
