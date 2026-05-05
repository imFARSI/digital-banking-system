<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Represents a debit, credit, or prepaid card issued to a user's account
class Card extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'account_id',
        'card_number',
        'card_type',
        'network',
        'cardholder_name',
        'expiry_month',
        'expiry_year',
        'cvv_hash',
        'credit_limit',
        'daily_limit',
        'status',
        'issued_at',
    ];

    // Auto-cast column types for safe handling
    protected $casts = [
        'credit_limit' => 'decimal:2',
        'daily_limit'  => 'decimal:2',
        'issued_at'    => 'date',
    ];

    // CVV hash is never exposed in API responses or serialization
    protected $hidden = [
        'cvv_hash',
    ];

    // Each card belongs to exactly one bank account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Returns true if the card is currently usable
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Returns a masked card number showing only the last 4 digits
    public function getMaskedNumberAttribute()
    {
        return '****-****-****-' . substr($this->card_number, -4);
    }
}
