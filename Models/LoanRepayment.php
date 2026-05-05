<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Represents a single monthly installment in a loan repayment schedule
class LoanRepayment extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'loan_id',
        'installment_no',
        'due_date',
        'amount_due',
        'amount_paid',
        'paid_at',
        'status',
        'transaction_id',
    ];

    // Cast date and decimal fields for safe handling
    protected $casts = [
        'amount_due'  => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date'    => 'date',
        'paid_at'     => 'datetime',
    ];

    // The loan this installment belongs to
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // The transaction record created when this installment was paid
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
