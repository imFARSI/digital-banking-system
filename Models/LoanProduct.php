<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Defines a bank's loan product offering (e.g. Home Loan, Personal Loan)
class LoanProduct extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'name',
        'type',
        'interest_rate',
        'min_amount',
        'max_amount',
        'min_tenure_months',
        'max_tenure_months',
        'is_active',
    ];

    // Cast numeric and boolean fields for safe comparisons
    protected $casts = [
        'interest_rate' => 'decimal:2',
        'min_amount'    => 'decimal:2',
        'max_amount'    => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    // All loans that were applied under this product type
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
