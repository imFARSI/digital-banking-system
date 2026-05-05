<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsPlan extends Model
{
    protected $fillable = [
        'account_id',
        'plan_type',
        'deposit_amount',
        'interest_rate',
        'tenure_months',
        'maturity_date',
        'maturity_amount',
        'status',
        'started_at',
    ];

    protected $casts = [
        'deposit_amount'  => 'decimal:2',
        'interest_rate'   => 'decimal:2',
        'maturity_amount' => 'decimal:2',
        'maturity_date'   => 'date',
        'started_at'      => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function isDps()
    {
        return $this->plan_type === 'dps';
    }

    public function isFdr()
    {
        return $this->plan_type === 'fdr';
    }

    // Calculates maturity amount: DPS=6%, FDR=7.5% simple interest
    public static function calculateMaturity($amount, $type, $tenure)
    {
        $rate = ($type === 'fdr') ? 7.5 : 6.0;
        $maturity = $amount * (1 + ($rate / 100) * ($tenure / 12));
        return [
            'rate' => $rate,
            'amount' => $maturity
        ];
    }
}
