<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Represents a bill category such as Electricity, Water, Internet, or Mobile Recharge
class BillCategory extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'name',
        'icon',
        'is_active',
    ];

    // Cast is_active to boolean for conditional logic
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // All payment records under this bill category
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
