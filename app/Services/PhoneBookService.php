<?php

namespace App\Services;

use App\Http\Requests\OffsetLimitPhoneBookRequest;
use App\Http\Requests\StorePhoneBookRequest;
use App\Http\Requests\UpdatePhoneBookRequest;
use App\Models\PhoneBook;

class PhoneBookService
{
    public function index(OffsetLimitPhoneBookRequest $request)
    {
        return PhoneBook::with('country')
            ->with('timezone')
            ->offset($request->input('offset'))
            ->limit($request->input('limit'))
            ->orderByDesc('id')
            ->get();
    }

    public function store(StorePhoneBookRequest $request)
    {
        return PhoneBook::create($request->validated());
    }

    public function show(PhoneBook $phoneBook)
    {
        return $phoneBook;
    }

    public function update(UpdatePhoneBookRequest $request, PhoneBook $phoneBook)
    {
        return $phoneBook->update($request->validated());
    }

    public function destroy(PhoneBook $phoneBook)
    {
        return $phoneBook->delete();

    }
}
