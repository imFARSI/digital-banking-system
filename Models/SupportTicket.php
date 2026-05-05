<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'ticket_number',
        'category',
        'subject',
        'priority',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }

    // Generates a unique ticket number: TKT-<uniqid>
    public static function generateTicketNumber()
    {
        return 'TKT-' . strtoupper(uniqid());
    }

    public function isOpen()
    {
        return in_array($this->status, ['open', 'in_progress']);
    }
}
