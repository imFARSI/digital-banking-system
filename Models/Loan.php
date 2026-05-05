<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'loan_product_id',
        'principal',
        'interest_rate',
        'tenure_months',
        'monthly_installment',
        'outstanding_balance',
        'disbursed_amount',
        'status',
        'applied_at',
        'approved_at',
        'disbursed_at',
        'maturity_date',
    ];

    protected $casts = [
        'principal'           => 'decimal:2',
        'interest_rate'       => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'disbursed_amount'    => 'decimal:2',
        'applied_at'          => 'datetime',
        'approved_at'         => 'datetime',
        'disbursed_at'        => 'datetime',
        'maturity_date'       => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    // Calculates total payable and monthly installment using simple interest
    public static function calculateTotals($principal, $interestRate, $tenureMonths)
    {
        $totalInterest = ($principal * $interestRate * $tenureMonths) / 1200;
        $totalPayable  = $principal + $totalInterest;
        $monthly       = $totalPayable / $tenureMonths;

        return [
            'total'   => $totalPayable,
            'monthly' => $monthly
        ];
    }

    // Approves loan: credits account, sets status to active, creates repayment rows
    public function disburse()
    {
        if ($this->status !== 'pending') return false;

        $this->account->increment('balance', $this->principal);

        $this->update([
            'status'        => 'active',
            'approved_at'   => now(),
            'disbursed_at'  => now(),
            'maturity_date' => now()->addMonths($this->tenure_months),
        ]);

        for ($i = 1; $i <= $this->tenure_months; $i++) {
            LoanRepayment::create([
                'loan_id'        => $this->id,
                'installment_no' => $i,
                'due_date'       => now()->addMonths($i)->toDateString(),
                'amount_due'     => $this->monthly_installment,
                'status'         => 'pending',
            ]);
        }

        return true;
    }
}
