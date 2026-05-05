<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Represents a completed payment transaction (bill or mobile recharge)
class Payment extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'account_id',
        'transaction_id',
        'bill_category_id',
        'payment_type',
        'payee_reference',
        'amount',
        'fee',
        'status',
        'paid_at',
    ];

    // Cast decimal and datetime columns for accurate output
    protected $casts = [
        'amount'  => 'decimal:2',
        'fee'     => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // The bank account this payment was charged from
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // The underlying transaction record linked to this payment
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // The bill category (electricity, water, mobile, etc.)
    public function billCategory()
    {
        return $this->belongsTo(BillCategory::class);
    }
}
