<?php
namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'changed_at',
    ];

    public $timestamps = true;

    protected $dates = ['changed_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
