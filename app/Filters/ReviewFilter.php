<?php

// app/Filters/ReviewFilter.php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ReviewFilter extends QueryFilter
{
    public function product_id($id)
    {
        return $this->builder->where('product_id', $id);
    }

    public function user_id($id)
    {
        return $this->builder->where('user_id', $id);
    }

    public function rating($value)
    {
        return $this->builder->where('rating', $value);
    }

    public function before_date($date)
    {
        return $this->builder->whereDate('created_at', '<=', $date);
    }

    public function after_date($date)
    {
        return $this->builder->whereDate('created_at', '>=', $date);
    }
}