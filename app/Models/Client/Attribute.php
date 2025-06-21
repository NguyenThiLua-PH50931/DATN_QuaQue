<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $table = 'attributes';

    protected $fillable = [
        'name',
        'slug',
    ];

    // Giá trị của thuộc tính này
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
