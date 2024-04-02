<?php

namespace App\Services;

use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CountryService
{
    public function insertCountries()
    {
        try {
            $client = new Client();
            $response = $client->get('http://country.io/continent.json');
            $countries = json_decode($response->getBody(), true);

            $countriesInserted = 0;

            foreach ($countries as $countryCode => $continent) {
                $existingCountry = Country::where('country_code', $countryCode)->first();

                if (!$existingCountry) {
                    Country::create([
                        'country_code' => $countryCode,
                        'continent_code' => $continent,
                    ]);
                    $countriesInserted++;
                }
            }

            if ($countriesInserted > 0) {
                return $countriesInserted . ' countries inserted successfully.';
            } else {
                return 'No new countries inserted. All countries are already present.';
            }
        } catch (\Exception $e) {
            Log::error('Error inserting countries: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
