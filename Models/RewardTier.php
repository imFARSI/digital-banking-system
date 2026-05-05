<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Defines loyalty tiers (e.g. Bronze, Silver, Gold) with point thresholds
class RewardTier extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'name',
        'min_points',
        'cashback_rate',
    ];

    // Cast types for accurate comparisons and calculations
    protected $casts = [
        'min_points'    => 'integer',
        'cashback_rate' => 'decimal:2',
    ];

    // All reward records that belong to this tier
    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }
}
