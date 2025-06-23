<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $table = 'review_replies';

    protected $fillable = [
        'review_id', 'admin_id', 'reply',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }
}