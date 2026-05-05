<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tracks a user's loyalty reward points and their assigned tier
class Reward extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'user_id',
        'reward_tier_id',
        'total_points',
        'redeemed_points',
    ];

    // Ensure point values are always integers
    protected $casts = [
        'total_points'    => 'integer',
        'redeemed_points' => 'integer',
    ];

    // The user who owns this reward record
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The tier (Bronze/Silver/Gold) this user currently belongs to
    public function rewardTier()
    {
        return $this->belongsTo(RewardTier::class);
    }
}
