<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'reference_code',
        'type',
        'sender_account_id',
        'receiver_account_id',
        'amount',
        'fee',
        'currency',
        'status',
        'description',
        'ip_address',
        'processed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'fee'          => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function loanRepayment()
    {
        return $this->hasOne(LoanRepayment::class);
    }

    // Generates a unique TXN-xxxx reference code for each transaction
    public static function generateReferenceCode()
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . mt_rand(1000, 9999);
    }

    public function markCompleted()
    {
        $this->update([
            'status'       => 'completed',
            'processed_at' => now(),
        ]);
    }

    public function markFailed()
    {
        $this->update([
            'status'       => 'failed',
            'processed_at' => now(),
        ]);
    }
}
