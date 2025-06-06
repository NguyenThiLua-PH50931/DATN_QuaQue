<?php

namespace App\Models\admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'admin_id',
        'reply',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
