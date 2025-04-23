<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
class PharmacyController extends Controller
{
    protected $mapsService;

    public function __construct(GoogleMapsService $mapsService)
    {
        $this->mapsService = $mapsService;
    }

    public function fetchPharmacies(Request $request)
    {
        $location = $request->input('location', '55.7558,37.6176');
        $radius = $request->input('radius', 5000);

        $pharmacies = $this->mapsService->searchPharmacies($location, $radius);
//в цикл он уже не заходит
        $count = 0;
        foreach ($pharmacies as $place) {
            $details = $this->mapsService->getPlaceDetails($place->place_id);

            Pharmacy::updateOrCreate(
                ['place_id' => $place->place_id],
                [
                    'name' => $place->name,
                    'address' => $place->vicinity ?? $details->formatted_address,
                    'latitude' => $place->geometry->location->lat,
                    'longitude' => $place->geometry->location->lng,
                    'opening_hours' => isset($details->opening_hours)
                        ? json_encode($details->opening_hours->weekday_text)
                        : null,
                    'phone' => $details->formatted_phone_number ?? null,
                    'website' => $details->website ?? null
                ]
            );

            $count++;
        }

        return response()->json([
            'message' => "Успешно сохранено $count аптек",
            'total' => $count
        ]);
    }
}
