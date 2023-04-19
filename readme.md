# Laravel Address Finder

This package provides a facade for address searching. Currently the only driver is available is [Loqate](https://www.loqate.com). There is also a mock driver for testing that does not communicate with any API's

## Installation

Via Composer

``` bash
$ composer require cyber-duck/laravel-address-finder
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
## Default: https://api.addressy.com/
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

### Retrieving Postzon record for the given postcode

```php
// $postcode: The postcode to use to search with
Address::postzon($postcode);
```

**Example**

```php
CyberDuck\AddressFinder\Facades\Address::postzon('N7 7PH')->get();

/*
[
    'Easting' => 530915,
    'Northing' => 186310,
    'Latitude' => 51.560487,
    'Longitude' => -0.112808,
    'OsGrid' => 'TQ 30915 86310',
    'CountryCode' => '921',
    'NewCountryCode' => 'E92000001',
    'CountryName' => 'England',
    'CountyCode' => '',
    'NewCountyCode' => 'E99999999',
    'CountyName' => '(pseudo) England (UA/MD/LB)',
    'DistrictCode' => '',
    'NewDistrictCode' => 'E09000019',
    'DistrictName' => 'Islington',
    'WardCode' => '00AUGC',
    'NewWardCode' => 'E05013703',
    'WardName' => '',
    'NhsShaCode' => 'Q36',
    'NewNhsShaCode' => '',
    'NhsShaName' => 'London',
    'NhsPctCode' => '',
    'NewNhsPctCode' => 'E16000048',
    'NhsPctName' => 'Islington',
    'LeaCode' => '',
    'LeaName' => '',
    'GovernmentOfficeCode' => 'H',
    'GovernmentOfficeName' => 'London',
    'WestminsterConstituencyCode' => 'C36',
    'WestminsterConstituencyName' => 'Islington North',
    'WestminsterMP' => 'Jeremy Corbyn',
    'WestminsterParty' => 'Labour',
    'WestminsterConstituencyCode2010' => 'C36',
    'WestminsterConstituencyName2010' => 'Islington North',
    'LSOACode' => 'E01002730',
    'LSOAName' => 'Islington 007A',
    'MSOACode' => 'E02000560',
    'MSOAName' => 'Islington 007',
    'CCGCode' => '93C',
    'CCGName' => 'NHS North Central London CCG',
    'CCGAreaCode' => '',
    'CCGAreaName' => '',
    'CCGRegionCode' => '',
    'CCGRegionName' => '',
]
*/
```
