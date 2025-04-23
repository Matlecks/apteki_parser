<?php

namespace App\Console\Commands;

use App\Models\Pharmacy;
use App\Services\GoogleMapsService;
use Illuminate\Console\Command;

class FetchPharmacies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:pharmacies {location?} {radius?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch pharmacies from Google Maps';

    /**
     * Execute the console command.
     */
    public function handle(GoogleMapsService $mapsService)
    {
        $location = $this->argument('location') ?? '55.7558,37.6176';
        $radius = $this->argument('radius') ?? 5000;

        $this->info("Fetching pharmacies near $location within $radius meters...");

        $pharmacies = $mapsService->searchPharmacies($location, $radius);

        $this->info("Found " . count($pharmacies) . " pharmacies");

        $bar = $this->output->createProgressBar(count($pharmacies));

        foreach ($pharmacies as $place) {
            $details = $mapsService->getPlaceDetails($place->place_id);

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

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Pharmacies saved successfully!');
    }
}
