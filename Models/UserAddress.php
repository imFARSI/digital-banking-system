<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Stores the optional residential address details for a user
class UserAddress extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'user_id',
        'line1',
        'line2',
        'city',
        'district',
        'postal_code',
        'country',
    ];

    // The user who owns this address record
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
