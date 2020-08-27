# laravel-search

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This package provides a facade for address searching. Currently the only driver is available is [Loqate](https://www.loqate.com). There is also a mock driver for testing that does not communicate with any API's

## Installation

Via Composer

``` bash
$ composer require cyberduck/laravel-address-finder
```

## Usage

### Environment Variable

The following ENV variable are available

```dotenv

# Address Finder

## Default: mock. Available [mock, loqate]
ADDRESS_FINDER_DRIVER # This can be used to set the driver to use 
## Default: false. Available [true, false]
ADDRESS_FINDER_CACHE # This can be used to set if the response should be cached or not
## Default: same as CACHE_DRIVER
ADDRESS_FINDER_CACHE_DRIVER # If cacheing this setting can be used to override the default cache driver used by address finder

# Loquate

## Default: null
LOQATE_API_KEY # Required if using the loqate driver
## Default: https://api.addressy.com/Capture/Interactive/
LOQATE_API_BASE_URI # This can be used to overide the API based URI when using the loqate driver
```

### Searching Address

```php
// $query: The search string
// $countryCode: The country code to search for addresses within
// $groupId: Results can have children, if this is the case you can retrieve the results children by passing the ID
Address::suggestions($query, $countryCode, $groupId);
```

**Example**

```php
CyberDuck\AddressFinder\Facades\Address::suggestions('EC1N', 'GB', null)->get();

/*
[
 [
   "id" => "1",
   "text" => "London, United Kingdom, EC1N 8TB - 2 Addresses",
   "has_children" => true,
 ],
]
 */


CyberDuck\AddressFinder\Facades\Address::suggestions('EC1N', 'GB', 1)->get();

/*
[
    [
     "id" => "2",
     "text" => "1 Greville Street - Greville Street London, 1, United Kingdom, EC1N 8TB",
     "has_children" => false,
    ],
    [
      "id" => "3",
      "text" => "2 Greville Street - Greville Street London, 2, United Kingdom, EC1N 8TB",
      "has_children" => false,
    ],
]
 */
```

### Retrieving Address Details

```php
// $addressId: The id of the address found from the suggestions method
Address::details($addressId);
```

**Example**

```php
CyberDuck\AddressFinder\Facades\Address::details('2')->get();

/*
[
     "postal_code" => "EC1N 8TB",
     "province_code" => "",
     "state" => "",
     "city" => "London",
     "address_line_1" => "Greville Street",
     "address_line_2" => "1",
     "address_line_3" => "",
]
*/
```