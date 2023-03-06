<?php

namespace CyberDuck\AddressFinder\Drivers;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;
use Http;
use Illuminate\Http\Client\PendingRequest;

/**
 * Class LoqateDriver
 *
 * @package CyberDuck\AddressFinder\Drivers
 */
class LoqateDriver implements DriverContract
{
    /**
     * @var string
     */
    private $suggestionsEndpoint;

    /**
     * @var string
     */
    private $detailsEndpoint;

    /**
     * @var PendingRequest
     */
    private $client;

    /**
     * LoqateDriver constructor.
     */
    public function __construct()
    {
        $config = config('laravel-address-finder.loqate');
        $this->suggestionsEndpoint = $config['api']['endpoints']['suggestions'];
        $this->detailsEndpoint = $config['api']['endpoints']['details'];
        $this->client = Http::withOptions([
            'base_uri' => $config['api']['base_uri'],
            'query' => [
                'Key' => $config['api']['key'],
            ],
        ]);
    }

    /**
     * @param $query
     * @param $country
     * @param $group_id
     * @return Suggestions
     */
    public function suggestions($query, $country, $group_id): Suggestions
    {
        return $this->parseSuggestions($this->client->get(
            $this->suggestionsEndpoint,
            [
                'Container' => $group_id,
                'Text' => $query,
                'Countries' => $country,
            ]
        )->json());
    }

    /**
     * @param $response
     * @return Suggestions
     */
    public function parseSuggestions($response)
    {
        /** @var Suggestions $suggestions */
        $suggestions = app(Suggestions::class);

        foreach ($response['Items'] ?? [] as $item) {
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
     * @return Details
     */
    public function getDetails($id, bool $raw = false, bool $translated = false)
    {
        return $this->parseDetails($this->client->get(
            $this->detailsEndpoint,
            ['Id' => $id]
        )->json(), $raw, $translated);
    }

    /**
     * @param $response
     * @param bool $raw
     * @param bool $translated
     * @return Details|array
     */
    public function parseDetails($response, bool $raw = false, bool $translated = false)
    {
        /** @var Details $details */
        $details = app(Details::class);

        $addressDetails = array_first($response['Items'], function ($item) use ($translated) {
            return ! $translated || $item['Language'] === 'ENG';
        });

        if (! $addressDetails) {
            return $details;
        }

        return $raw ? $addressDetails : $details->setPostalCode($addressDetails['PostalCode'] ?? '')
            ->setProvinceCode($addressDetails['ProvinceCode'] ?? '')
            ->setCompany($addressDetails['Company'])
            ->setCity($addressDetails['City'])
            ->setLine1($addressDetails['Line1'])
            ->setLine2($addressDetails['Line2'])
            ->setLine3($addressDetails['Line3']);
    }
}
