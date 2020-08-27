<?php

namespace CyberDuck\AddressFinder;

/**
 * Class Suggestions
 *
 * @package CyberDuck\AddressFinder
 */
class Suggestions
{
    /**
     * @var array
     */
    private $suggestions = [];

    /**
     * @param $id
     * @param $text
     * @param $hasChildren
     */
    public function add($id, $text, $hasChildren)
    {
        $this->suggestions[] = [
            'id' => $id,
            'text' => $text,
            'has_children' => $hasChildren
        ];
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->suggestions;
    }
}
