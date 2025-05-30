<?php

namespace App\Models\BE;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;

    protected $table = 'regions';
    protected $fillable = ['name', 'slug'];
    protected $dates = ['deleted_at'];

}
