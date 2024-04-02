<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    use HasFactory;

    const CREATED_AT = 'insertedOn';
    const UPDATED_AT = 'updatedOn';

    protected $fillable = [
        'country_id',
        'timezone_id',
        'first_name',
        'last_name',
        'phone_number',
        'insertedOn',
        'updatedOn',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id'); // links this->course_id to courses.id
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class, 'timezone_id', 'id'); // links this->course_id to courses.id
    }
}
