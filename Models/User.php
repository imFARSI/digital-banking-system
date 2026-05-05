<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SystemNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'nid',
        'date_of_birth', 'gender', 'role', 'status', 'reward_points',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'password'          => 'hashed',
            'reward_points'     => 'integer',
        ];
    }

    // Increments reward_points on the user record by given amount
    public function addRewardPoints(int $points = 5)
    {
        $this->increment('reward_points', $points);
    }

    // Sends a database notification via SystemNotification
    public function sendNotification(array $data)
    {
        $notification = new SystemNotification($data);
        $this->notify($notification);
        return $notification;
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reward()
    {
        return $this->hasOne(Reward::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
