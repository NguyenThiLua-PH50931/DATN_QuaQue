<?php

namespace App\Models\Admin\Traits;

use Carbon\Carbon;

trait BannerTimeValidation
{
    public function hasOverlappingActiveBanner()
    {
        $query = static::where('active', true)
            ->where('location', $this->location)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('display_at', '<=', $this->display_at)
                        ->where('display_end_at', '>=', $this->display_at);
                })->orWhere(function ($q) {
                    $q->where('display_at', '<=', $this->display_end_at)
                        ->where('display_end_at', '>=', $this->display_end_at);
                })->orWhere(function ($q) {
                    $q->where('display_at', '>=', $this->display_at)
                        ->where('display_end_at', '<=', $this->display_end_at);
                });
            });

        // Khi cập nhật, loại trừ banner hiện tại khỏi quá trình kiểm tra
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        // Nếu là banner slider, kiểm tra số lượng banner đang hoạt động
        if ($this->location === 'slider_banner') {
            $activeSliderCount = $query->count();
            return $activeSliderCount >= 4;
        }

        // Đối với các loại banner khác, không cho phép trùng lặp
        return $query->exists();
    }

    public function canBeActivated()
    {
        if (!$this->active) {
            return true;
        }

        return !$this->hasOverlappingActiveBanner();
    }
} 