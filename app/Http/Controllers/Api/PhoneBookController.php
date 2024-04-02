<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OffsetLimitPhoneBookRequest;
use App\Http\Requests\StorePhoneBookRequest;
use App\Http\Requests\UpdatePhoneBookRequest;
use App\Models\PhoneBook;
use App\Services\PhoneBookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *     schema="PhoneBook",
 *     required={"country_id", "timezone_id", "first_name", "phone_number", "insertedOn", "updatedOn"},
 *     @OA\Property(
 *         property="country_id",
 *         type="integer",
 *         example=1,
 *         description="ID of the country"
 *     ),
 *     @OA\Property(
 *         property="timezone_id",
 *         type="integer",
 *         example=1,
 *         description="ID of the timezone"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         example="John",
 *         maxLength=255,
 *         description="First name of the person"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         example="Doe",
 *         maxLength=255,
 *         description="Last name of the person"
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         example="+01 123 4567890",
 *         pattern="^\+\d{2}\s\d{3}\s\d{9}$",
 *         description="Phone number of the person (format: +XX XXX XXXXXXXX)"
 *     ),
 *     @OA\Property(
 *         property="insertedOn",
 *         type="string",
 *         format="date",
 *         example="2022-04-02",
 *         description="Date of insertion"
 *     ),
 *     @OA\Property(
 *         property="updatedOn",
 *         type="string",
 *         format="date",
 *         example="2022-04-02",
 *         description="Date of update"
 *     )
 * )
 */
class PhoneBookController extends Controller
{
    protected $phoneBookService;

    public function __construct(PhoneBookService $phoneBookService)
    {
        $this->phoneBookService = $phoneBookService;
    }

    /**
     * @OA\Get(
     *     path="/api/phone-books",
     *     operationId="getPhoneBooks",
     *     tags={"PhoneBooks"},
     *     summary="Get all phone book entries",
     *     description="Retrieves all phone book entries.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset for pagination",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit per page for pagination (max: 20)",
     *         required=true,
     *         @OA\Schema(type="integer", maximum=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of phone book entries",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PhoneBook")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index(OffsetLimitPhoneBookRequest $request)
    {
        try {
            $phoneBooks = $this->phoneBookService->index($request);

            return response()->json($phoneBooks);
        } catch (\Exception $e) {
            Log::error('Error getting phone book entry: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/phone-books",
     *     operationId="storePhoneBook",
     *     tags={"PhoneBooks"},
     *     summary="Store a new phone book entry",
     *     description="Stores a new phone book entry.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country_id", "timezone_id", "first_name", "phone_number", "insertedOn", "updatedOn"},
     *             @OA\Property(property="country_id", type="integer", description="ID of the country"),
     *             @OA\Property(property="timezone_id", type="integer", description="ID of the timezone"),
     *             @OA\Property(property="first_name", type="string", maxLength=255, description="First name"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, description="Last name"),
     *             @OA\Property(property="phone_number", type="string", pattern="^\+\d{2}\s\d{3}\s\d{9}$", description="Phone number (format: +XX XXX XXXXXXXX)"),
     *             @OA\Property(property="insertedOn", type="string", format="date", description="Date of insertion"),
     *             @OA\Property(property="updatedOn", type="string", format="date", description="Date of update")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Phone book entry stored successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PhoneBook")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(StorePhoneBookRequest $request)
    {
        try {
            $phoneBook = $this->phoneBookService->store($request);

            return response()->json($phoneBook, 201);
        } catch (\Exception $e) {
            Log::error('Error storing phone book entry: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/phone-books/{id}",
     *     operationId="getPhoneBookById",
     *     tags={"PhoneBooks"},
     *     summary="Get a specific phone book entry",
     *     description="Retrieves a specific phone book entry by its ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the phone book entry",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone book entry found",
     *         @OA\JsonContent(ref="#/components/schemas/PhoneBook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Phone book entry not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show(PhoneBook $phoneBook)
    {
        try {
            $phoneBook = $this->phoneBookService->show($phoneBook);

            return response()->json($phoneBook);
        } catch (\Exception $e) {
            Log::error('Error showing phone book entry: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/phone-books/{id}",
     *     operationId="updatePhoneBook",
     *     tags={"PhoneBooks"},
     *     summary="Update an existing phone book entry",
     *     description="Updates an existing phone book entry.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the phone book entry to update",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country_id", "timezone_id", "first_name", "phone_number", "insertedOn", "updatedOn"},
     *             @OA\Property(property="country_id", type="integer", description="ID of the country"),
     *             @OA\Property(property="timezone_id", type="integer", description="ID of the timezone"),
     *             @OA\Property(property="first_name", type="string", maxLength=255, description="First name"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, description="Last name"),
     *             @OA\Property(property="phone_number", type="string", pattern="^\+\d{2}\s\d{3}\s\d{9}$", description="Phone number (format: +XX XXX XXXXXXXX)"),
     *             @OA\Property(property="insertedOn", type="string", format="date", description="Date of insertion"),
     *             @OA\Property(property="updatedOn", type="string", format="date", description="Date of update")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone book entry updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PhoneBook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Phone book entry not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(UpdatePhoneBookRequest $request, PhoneBook $phoneBook)
    {
        try {
            $phoneBook = $this->phoneBookService->update($request, $phoneBook);

            return response()->json($phoneBook);
        } catch (\Exception $e) {
            Log::error('Error updating phone book entry: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/phone-books/{id}",
     *     operationId="deletePhoneBook",
     *     tags={"PhoneBooks"},
     *     summary="Delete a phone book entry",
     *     description="Deletes a phone book entry by its ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the phone book entry",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Phone book entry deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Phone book entry not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy(PhoneBook $phoneBook)
    {
        try {
            $this->phoneBookService->destroy($phoneBook);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting phone book entry: ' . $e->getMessage());

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
