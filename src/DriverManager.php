<?php

namespace CyberDuck\AddressFinder;

use CyberDuck\AddressFinder\Drivers\DriverContract;
use CyberDuck\AddressFinder\Drivers\LoqateDriver;
use CyberDuck\AddressFinder\Drivers\MockDriver;
use Exception;
use Illuminate\Support\Manager;

/**
 * Class DriverManager
 *
 * @package CyberDuck\AddressFinder
 */
class DriverManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param string|null $name
     * @return mixed
     */
    public function engine($name = null)
    {
        return $this->driver($name);
    }

    /**
     * @return DriverContract
     * @throws Exception
     */
    public function createLoqateDriver()
    {
        return app(LoqateDriver::class);
    }

    /**
     * @return DriverContract
     * @throws Exception
     */
    public function createMockDriver()
    {
        return app(MockDriver::class);
    }

    /**
     * Get the default Scout driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container['config']['laravel-address-finder.driver'];
    }
}
