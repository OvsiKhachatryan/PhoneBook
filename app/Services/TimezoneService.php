<?php

namespace App\Services;

use App\Models\Timezone;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TimezoneService
{
    public function insertTimezones()
    {
        try {
            $client = new Client();
            $response = $client->get('http://worldtimeapi.org/api/timezone');
            $timezones = json_decode($response->getBody(), true);

            $timezonesInserted = 0;

            foreach ($timezones as $timezone) {
                $existingTimezone = Timezone::where('identifier', $timezone)->first();

                if (!$existingTimezone) {
                    Timezone::create([
                        'identifier' => $timezone,
                    ]);
                    $timezonesInserted++;
                }
            }

            if ($timezonesInserted > 0) {
                return $timezonesInserted . ' timezones inserted successfully.';
            } else {
                return 'No new timezones inserted. All timezones are already present.';
            }
        } catch (\Exception $e) {
            Log::error('Error inserting timezones: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

}
