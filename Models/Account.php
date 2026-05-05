<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'account_type',
        'balance',
        'currency',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'balance'   => 'decimal:2',
        'opened_at' => 'date',
        'closed_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_account_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_account_id');
    }

    public function savingsPlans()
    {
        return $this->hasMany(SavingsPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    // Generates a unique FNXxxxxxxxxxx account number
    public static function generateAccountNumber()
    {
        do {
            $number = 'FNX' . mt_rand(1000000000, 9999999999);
        } while (self::where('account_number', $number)->exists());

        return $number;
    }
}
