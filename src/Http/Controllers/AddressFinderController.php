<?php

namespace CyberDuck\AddressFinder\Http\Controllers;

use App\Address\AddressesContract;
use App\Address\LoqateParser as Parser;
use CyberDuck\AddressFinder\Facades\Address;
use Illuminate\Http\Request;

/**
 * Class AddressFinderController
 *
 * @package CyberDuck\AddressFinder\Http\Controllers
 */
class AddressFinderController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function suggestions(Request $request)
    {
        return Address::suggestions(
            $request->input('query'),
            $request->input('country'),
            $request->input('addressId')
        )->get();
    }

    /**
     * @param string $addressId
     * @return mixed
     */
    public function addressDetails($addressId)
    {
        return Address::details(
            $addressId
        )->get();
    }
}
