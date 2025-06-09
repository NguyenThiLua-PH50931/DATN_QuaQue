<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'link',
        'active',
        'display_at',
    ];

    protected $dates = [
        'deleted_at',
        'display_at',
        'created_at',
        'updated_at',
    ];
}
