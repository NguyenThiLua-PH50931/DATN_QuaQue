<?php
namespace App\Models;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;

class ProductSearch extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'keyword',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class); // Đã ở App\Models rồi
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
