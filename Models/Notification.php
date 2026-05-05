<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Custom notification model for app-level alerts stored in the database
class Notification extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'is_read',
        'read_at',
    ];

    // Cast boolean and datetime fields for accurate output
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // The user this notification was sent to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Marks this notification as read and records the timestamp
    public function markRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
