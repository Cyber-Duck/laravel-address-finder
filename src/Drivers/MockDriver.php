<?php

namespace CyberDuck\AddressFinder\Drivers;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;
use Faker\Generator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class MockDriver
 *
 * @package CyberDuck\AddressFinder\Drivers
 */
class MockDriver implements DriverContract
{
    /**
     * @var array
     */
    private $mockedAddress;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->mockedAddress = [
            'address_line_1' => $faker->address,
            'address_line_2' => $faker->address . ' - 2',
            'address_line_3' => $faker->address . ' - 3',
            'postal_code' => $faker->postcode,
            'city' => $faker->city,
        ];
    }

    /**
     * @param $query
     * @param $country
     * @param $group_id
     * @param bool $raw
     * @return Suggestions|array
     */
    public function suggestions($query, $country, $group_id, bool $raw = false): Suggestions|array
    {
        if (!empty($group_id)) {
            return $this->parseSuggestions($this->getAddressChildrenResponse(), $raw);
        }

        return $this->parseSuggestions($this->getSuggestedAddressesResponse($country), $raw);
    }

    /**
     * @return array
     */
    private function getSuggestedAddressesResponse($country)
    {
        $suggestions = [
            'default' => [
                'Items' => [
                    [
                        'Id' => 'default',
                        'Type' => 'Street',
                        'Text' => '20A Example Street',
                        'Description' => '20A Example Street Example City, Example Country, 1234',
                    ],
                ],
            ],
            'GB' => [
                'Items' => [
                    [
                        'Id' => '1',
                        'Type' => 'Street',
                        'Text' => '32B Greville Street',
                        'Description' => 'Greville Street London, 32B, United Kingdom, EC1N 8TB',
                    ],
                ],
            ],
            'ES' => [
                'Items' => [
                    [
                        'Id' => '2',
                        'Type' => 'Street',
                        'Text' => 'Calle de Ulldecona, 15',
                        'Description' => "Calle de Ulldecona, 15, 08007 Barcelona, Spain",
                    ],
                ],
            ],
            'FR' => [
                'Items' => [
                    [
                        'Id' => '3',
                        'Type' => 'Street',
                        'Text' => '48 Boulevard Jourdan',
                        'Description' => "DELTA, Boulevard Jourdan, 48, 75014 Paris, France",
                    ],
                ],
            ],
            'DE' => [
                'Items' => [
                    [
                        'Id' => '4',
                        'Type' => 'Street',
                        'Text' => '32 Pariser Platz, 10117',
                        'Description' => "Pariser Platz, 32, 10117 Berlin, Germany",
                    ],
                ],
            ],
        ];

        return $suggestions[$country] ?? $suggestions['default'];
    }

    /**
     * @return array
     */
    private function getAddressChildrenResponse()
    {
        return [
            'Items' => [
                [
                    'Id' => 'ES|TT|A|17240004045469|1',
                    'Type' => 'Address',
                    'Text' => $this->mockedAddress['address_line_1'] . ' ' . Str::random(2),
                    'Highlight' => '',
                    'Description' => "{$this->mockedAddress['postal_code']} {$this->mockedAddress['city']}",
                ],
                [
                    'Id' => 'ES|TT|A|17240010299823|1S',
                    'Type' => 'Address',
                    'Text' => $this->mockedAddress['address_line_1'] . ' ' . Str::random(2),
                    'Highlight' => '',
                    'Description' => "{$this->mockedAddress['postal_code']} {$this->mockedAddress['city']}",
                ],
            ],
        ];
    }

    /**
     * @param $response
     * @param bool $raw
     * @return Suggestions|array
     */
    public function parseSuggestions($response, bool $raw = false): Suggestions|array
    {
        /** @var Suggestions $suggestions */
        $suggestions = app(Suggestions::class);

        if ($raw) {
            return $response['Items'] ?? [];
        }

        foreach($response['Items'] ?? [] as $item) {
            if (!isset($item['Id'])) {
                continue;
            }

            $suggestions->add(
                $item['Id'],
                $item['Text'] . ($item['Description'] ? " - {$item['Description']}" : ''),
                $item['Type'],
                str_contains($item['Description'], 'Addresses')
            );
        }

        return $suggestions;
    }

    /**
     * @param $id
     * @param bool $raw
     * @param bool $translated
     * @param array $customFields
     * @return Details
     */
    public function getDetails($id, bool $raw = false, bool $translated = false, array $customFields = [])
    {
        $addresses = [
            'default' => [
                "Items" => [
                    [
                        'Id' => 'default',
                        'DomesticId' => '8170404',
                        'Language' => 'ENG',
                        'LanguageAlternatives' => 'ENG',
                        'Department' => '',
                        'Company' => 'Test Addresses Ltd ©',
                        'SubBuilding' => '',
                        'BuildingNumber' => '',
                        'BuildingName' => '32A',
                        'SecondaryStreet' => '',
                        'Street' => 'Greville Street',
                        'Block' => '',
                        'Neighbourhood' => '',
                        'District' => '',
                        'City' => 'London',
                        'Line1' => '32A Greville Street',
                        'Line2' => '',
                        'Line3' => '',
                        'Line4' => '',
                        'Line5' => '',
                        'AdminAreaName' => 'Camden',
                        'AdminAreaCode' => '',
                        'Province' => '',
                        'ProvinceName' => '',
                        'ProvinceCode' => '',
                        'PostalCode' => 'EC1N 8TB',
                        'CountryName' => 'United Kingdom',
                        'CountryIso2' => 'GB',
                        'CountryIso3' => 'GBR',
                        'CountryIsoNumber' => 826,
                        'SortingNumber1' => '74116',
                        'SortingNumber2' => '',
                        'Barcode' => '(EC1N8TB2R9)',
                        'POBoxNumber' => '',
                        'Label' => ' 32A Greville Street LONDON EC1N 8TB UNITED KINGDOM ',
                        'Type' => 'Residential',
                        'DataLevel' => 'Premise',
                        'Field1' => '51.832993',
                        'Field2' => '-3.041141',
                    ],
                ],
            ],
            "1" => [
                "Items" => [
                    [
                        'Id' => '1',
                        'DomesticId' => '8170414',
                        'Language' => 'ENG',
                        'LanguageAlternatives' => 'ENG',
                        'Department' => '',
                        'Company' => 'Test Addresses Ltd ©',
                        'SubBuilding' => '',
                        'BuildingNumber' => '',
                        'BuildingName' => '32A',
                        'SecondaryStreet' => '',
                        'Street' => 'Greville Street',
                        'Block' => '',
                        'Neighbourhood' => '',
                        'District' => '',
                        'City' => 'London',
                        'Line1' => '32A Greville Street',
                        'Line2' => '',
                        'Line3' => '',
                        'Line4' => '',
                        'Line5' => '',
                        'AdminAreaName' => 'Camden',
                        'AdminAreaCode' => '',
                        'Province' => '',
                        'ProvinceName' => '',
                        'ProvinceCode' => '',
                        'PostalCode' => 'EC1N 8TB',
                        'CountryName' => 'United Kingdom',
                        'CountryIso2' => 'GB',
                        'CountryIso3' => 'GBR',
                        'CountryIsoNumber' => 826,
                        'SortingNumber1' => '74116',
                        'SortingNumber2' => '',
                        'Barcode' => '(EC1N8TB2R9)',
                        'POBoxNumber' => '',
                        'Label' => ' 32A Greville Street LONDON EC1N 8TB UNITED KINGDOM ',
                        'Type' => 'Residential',
                        'DataLevel' => 'Premise',
                        'Field1' => '50.832993',
                        'Field2' => '-0.041141',
                    ],
                ],
            ],
            "2" => [
                "Items" => [
                    [
                        'Id' => '2',
                        'DomesticId' => '8170402',
                        'Language' => 'ENG',
                        'LanguageAlternatives' => 'ENG',
                        'Department' => '',
                        'Company' => 'Test Addresses Ltd ©',
                        'SubBuilding' => '',
                        'BuildingNumber' => '',
                        'BuildingName' => '32A',
                        'SecondaryStreet' => '',
                        'Street' => 'Ulldecona',
                        'Block' => '',
                        'Neighbourhood' => '',
                        'District' => '',
                        'City' => 'Roquetes',
                        'Line1' => 'Calle de Ulldecona',
                        'Line2' => '15',
                        "Line3" => "",
                        'Line4' => '',
                        'Line5' => '',
                        'AdminAreaName' => 'Camden',
                        'AdminAreaCode' => '',
                        'Province' => '',
                        'ProvinceName' => '',
                        'ProvinceCode' => '',
                        'PostalCode' => '43520',
                        'CountryName' => 'United Kingdom',
                        'CountryIso2' => 'GB',
                        'CountryIso3' => 'GBR',
                        'CountryIsoNumber' => 826,
                        'SortingNumber1' => '74116',
                        'SortingNumber2' => '',
                        'Barcode' => '(EC1N8TB2R9)',
                        'POBoxNumber' => '',
                        'Label' => ' 15 Calle de Ulldecona PARIS 43520 FRANCE',
                        'Type' => 'Residential',
                        'DataLevel' => 'Premise',
                        'Field1' => '48.832993',
                        'Field2' => '0.041141',
                    ],
                ],
            ],
            "3" => [
                "Items" => [
                    [
                        'Id' => '3',
                        'DomesticId' => '8170403',
                        'Language' => 'ENG',
                        'LanguageAlternatives' => 'ENG',
                        'Department' => '',
                        'Company' => 'Test Addresses Ltd ©',
                        'SubBuilding' => '',
                        'BuildingNumber' => '',
                        'BuildingName' => '32A',
                        'SecondaryStreet' => '',
                        'Street' => 'Boulevard Jourdan',
                        'Block' => '',
                        'Neighbourhood' => '',
                        'District' => '',
                        'City' => 'Paris',
                        'Line1' => 'Boulevard Jourdan',
                        'Line2' => '48',
                        "Line3" => "",
                        'Line4' => '',
                        'Line5' => '',
                        'AdminAreaName' => 'Camden',
                        'AdminAreaCode' => '',
                        'Province' => '',
                        'ProvinceName' => '',
                        'ProvinceCode' => '',
                        'PostalCode' => '75014',
                        'CountryName' => 'United Kingdom',
                        'CountryIso2' => 'GB',
                        'CountryIso3' => 'GBR',
                        'CountryIsoNumber' => 826,
                        'SortingNumber1' => '74116',
                        'SortingNumber2' => '',
                        'Barcode' => '(EC1N8TB2R9)',
                        'POBoxNumber' => '',
                        'Label' => ' 15 Calle de Ulldecona PARIS 43520 FRANCE',
                        'Type' => 'Residential',
                        'DataLevel' => 'Premise',
                        'Field1' => '55.832993',
                        'Field2' => '-1.041141',
                    ],
                ],
            ],
            "4" => [
                "Items" => [
                    [
                        'Id' => '4',
                        'DomesticId' => '8170403',
                        'Language' => 'ENG',
                        'LanguageAlternatives' => 'ENG',
                        'Department' => '',
                        'Company' => 'Test Addresses Ltd ©',
                        'SubBuilding' => '',
                        'BuildingNumber' => '',
                        'BuildingName' => '32A',
                        'SecondaryStreet' => '',
                        'Street' => 'Pariser Platz',
                        'Block' => '',
                        'Neighbourhood' => '',
                        'District' => '',
                        'City' => 'Berlin',
                        'Line1' => "Pariser Platz",
                        "Line2" => "32",
                        "Line3" => "",
                        'Line4' => '',
                        'Line5' => '',
                        'AdminAreaName' => 'Camden',
                        'AdminAreaCode' => '',
                        'Province' => '',
                        'ProvinceName' => '',
                        'ProvinceCode' => '',
                        "PostalCode" => "10117",
                        'CountryName' => 'United Kingdom',
                        'CountryIso2' => 'GB',
                        'CountryIso3' => 'GBR',
                        'CountryIsoNumber' => 826,
                        'SortingNumber1' => '74116',
                        'SortingNumber2' => '',
                        'Barcode' => '(EC1N8TB2R9)',
                        'POBoxNumber' => '',
                        'Label' => ' 15 Calle de Ulldecona PARIS 43520 FRANCE',
                        'Type' => 'Residential',
                        'DataLevel' => 'Premise',
                        'Field1' => '49.832993',
                        'Field2' => '1.041141',
                    ],
                ],
            ],

        ];

        return $this->parseDetails($addresses[$id], $raw, $translated, $customFields);
    }

    /**
     * @param $response
     * @param bool $raw
     * @param bool $translated
     * @param array $customFields
     * @return Details|array
     */
    public function parseDetails($response, bool $raw = false, bool $translated = false, array $customFields = [])
    {
        /** @var Details $details */
        $details = app(Details::class);

        $addressDetails = Arr::first($response['Items']) ?? null;

        if (! $addressDetails) {
            return $details;
        }

        $customFieldsResult = [];
        if (! empty($customFields)) {
            foreach ($customFields as $key => $value) {
                $newKey = Str::of($value)->replaceMatches('/[^A-Za-z0-9]++/', '')->lower()->toString();
                $customFieldsResult[$newKey] = $addressDetails['Field' . $key + 1] ?? '';
            }
        }

        return $raw ? $addressDetails : $details->setPostalCode($addressDetails['PostalCode'] ?? '')
            ->setProvinceCode($addressDetails['ProvinceCode'] ?? '')
            ->setCompany($addressDetails['Company'])
            ->setCity($addressDetails['City'])
            ->setLine1($addressDetails['Line1'])
            ->setLine2($addressDetails['Line2'])
            ->setLine3($addressDetails['Line3'])
            ->setCustomFields($customFieldsResult);
    }
}
