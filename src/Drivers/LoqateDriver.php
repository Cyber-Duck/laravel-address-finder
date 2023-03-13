<?php

namespace CyberDuck\AddressFinder\Drivers;

use CyberDuck\AddressFinder\Details;
use CyberDuck\AddressFinder\Suggestions;
use Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
     * @param bool $raw
     * @return Suggestions|array
     */
    public function suggestions($query, $country, $group_id, bool $raw = false): Suggestions|array
    {
        return $this->parseSuggestions($this->client->get(
            $this->suggestionsEndpoint,
            [
                'Container' => $group_id,
                'Text' => $query,
                'Countries' => $country,
            ]
        )->json(), $raw);
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
     * @param array $customFields
     * @return Details
     */
    public function getDetails($id, bool $raw = false, bool $translated = false, array $customFields = [])
    {
        return $this->parseDetails($this->client->get(
            $this->detailsEndpoint,
            $this->buildDetailsPayload($id, $customFields)
        )->json(), $raw, $translated, $customFields);
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

        $addressDetails = Arr::first($response['Items'], function ($item) use ($translated) {
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
            ->setLine3($addressDetails['Line3'])
            ->setCustomFields($this->buildCustomFieldsForResponse($customFields, $addressDetails));
    }

    /**
     * @param $id
     * @param array $customFields
     * @return array
     */
    private function buildDetailsPayload($id, array $customFields): array
    {
        $payload = ['Id' => $id];

        foreach ($customFields as $key => $value) {
            $payload['Field' . $key + 1 . 'Format'] = $value;
        }

        return $payload;
    }

    /**
     * @param array $customFields
     * @param array $addressDetails
     * @return array
     */
    private function buildCustomFieldsForResponse(array $customFields, array $addressDetails): array
    {
        $customFieldsResult = [];
        if (! empty($customFields)) {
            foreach ($customFields as $key => $value) {
                $newKey = Str::of($value)->replaceMatches('/[^A-Za-z0-9]++/', '')->lower()->toString();
                $customFieldsResult[$newKey] = $addressDetails['Field' . $key + 1] ?? '';
            }
        }

        return $customFieldsResult;
    }
}
