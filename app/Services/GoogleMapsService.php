<?php

namespace App\Services;

use Google\Client;
use Google\Service\MapsPlaces;
use Google\Service\MapsPlaces\GoogleMapsPlacesV1Circle;
use Google\Service\MapsPlaces\GoogleMapsPlacesV1SearchNearbyRequest;
use Google\Service\MapsPlaces\GoogleMapsPlacesV1SearchNearbyRequestLocationRestriction;
use Google\Service\MapsPlaces\GoogleTypeLatLng;
use Google\Service\MapsPlaces\Resource\Places as PlacesResource;

/**
 * Апи гугла на Places Api платный. Не вариант использовать его
 */
class GoogleMapsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Pharmacy Finder');
        $this->client->setDeveloperKey(config('services.google.key'));
        $this->service = new MapsPlaces($this->client);
    }

    public function searchPharmacies($location, $radius = 10000)
    {
        try {
            // Создаем объект запроса
            $request = new GoogleMapsPlacesV1SearchNearbyRequest();

            // Разбиваем location на latitude и longitude
            [$latitude, $longitude] = explode(',', $location);
            $center = new GoogleTypeLatLng();
            $center->setLatitude($latitude);
            $center->setLongitude($longitude);

            // Создаем объект Circle для locationRestriction
            $circle = new GoogleMapsPlacesV1Circle();
            $circle->setCenter($center);
            $circle->setRadius((float)$radius);

            // Устанавливаем параметры запроса
            $locationRestriction = new GoogleMapsPlacesV1SearchNearbyRequestLocationRestriction();
            $locationRestriction->setCircle($circle);
            $request->setLocationRestriction($locationRestriction);
            $request->setIncludedTypes(['drugstore', 'pharmacy', 'hospital']);
            $request->setLanguageCode('ru');
            $request->setMaxResultCount(100);
            $request->setRankPreference('DISTANCE');

            // Отправляем запрос
            $response = $this->service->places->searchNearby($request);

            return $response->getPlaces();
        } catch (\Exception $e) {
            logger()->error('Google Places API Error: ' . $e->getMessage());
            return [];
        }
    }


    public function getPlaceDetails($placeId)
    {
        try {
            $response = $this->service->places->get('places/' . $placeId, [
                'languageCode' => 'ru'
            ]);

            return $response;
        } catch (\Exception $e) {
            logger()->error('Google Places Details Error: ' . $e->getMessage());
            return null;
        }
    }
}
