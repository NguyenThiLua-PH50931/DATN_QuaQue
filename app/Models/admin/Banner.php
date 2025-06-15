<?php

namespace App\Models\admin;

use App\Models\admin\Traits\BannerTimeValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes, BannerTimeValidation;

    protected $fillable = [
        'title',
        'image',
        'link',
        'active',
        'display_at',
        'display_end_at',
        'location',
    ];

    protected $dates = [
        'deleted_at',
        'display_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'display_at' => 'datetime',
        'display_end_at' => 'datetime',
        'active' => 'boolean',
    ];
}
