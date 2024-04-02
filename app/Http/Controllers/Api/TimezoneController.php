<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TimezoneService;
use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    protected $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * @OA\Post(
     *     path="/api/timezones",
     *     operationId="insertTimezones",
     *     tags={"Timezones"},
     *     summary="Insert timezones into the database",
     *     description="Inserts timezones into the database from an external API.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Timezones inserted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function insertTimezones(TimezoneService $timezoneService)
    {
        try {
            $message = $timezoneService->insertTimezones();
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
