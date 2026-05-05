<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// A single reply message on a support ticket, sent by either a customer or admin
class TicketReply extends Model
{
    // Columns allowed for mass assignment
    protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_role',
        'message',
        'attachment',
        'is_internal',
    ];

    // is_internal flag marks admin-only notes not visible to the customer
    protected $casts = [
        'is_internal' => 'boolean',
    ];

    // The support ticket this reply belongs to
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    // The user (customer or admin) who sent this reply
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
