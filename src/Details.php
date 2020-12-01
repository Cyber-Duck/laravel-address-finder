<?php

namespace CyberDuck\AddressFinder;

/**
 * Class Details
 *
 * @package CyberDuck\AddressFinder
 */
class Details
{
    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $line1;

    /**
     * @var string
     */
    private $line2;

    /**
     * @var string
     */
    private $line3;

    /**
     * @var string
     */
    private $provinceCode;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $state;

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return Details
     */
    public function setPostalCode($postalCode): Details
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Details
     */
    public function setCity($city): Details
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     * @return Details
     */
    public function setCompany($company): Details
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @param mixed $line1
     * @return Details
     */
    public function setLine1($line1): Details
    {
        $this->line1 = $line1;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @param mixed $line2
     * @return Details
     */
    public function setLine2($line2): Details
    {
        $this->line2 = $line2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLine3()
    {
        return $this->line3;
    }

    /**
     * @param mixed $line3
     * @return Details
     */
    public function setLine3($line3): Details
    {
        $this->line3 = $line3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }

    /**
     * @param mixed $provinceCode
     * @return Details
     */
    public function setProvinceCode($provinceCode): Details
    {
        $this->provinceCode = $provinceCode;
        return $this;

    }

    /**
     * @param string $state
     * @return Details
     */
    public function setState(string $state): Details
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return array
     */
    public function get()
    {
        return [
            'postal_code' => $this->getPostalCode(),
            'province_code' => $this->getProvinceCode() ?? '',
            'state' => $this->getState() ?? '',
            'company' => $this->getCompany(),
            'city' => $this->getCity(),
            'address_line_1' => $this->getLine1(),
            'address_line_2' => $this->getLine2(),
            'address_line_3' => $this->getLine3(),
        ];
    }
}
