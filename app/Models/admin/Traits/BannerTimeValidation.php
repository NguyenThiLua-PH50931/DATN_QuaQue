<?php

namespace App\Models\Admin\Traits;

use Carbon\Carbon;

trait BannerTimeValidation
{
    public function hasOverlappingActiveBanner()
    {
        // Đảm bảo thời gian bắt đầu là 00:00:00 và kết thúc là 23:59:59
        $startTime = Carbon::parse($this->display_at)->startOfDay();
        $endTime = Carbon::parse($this->display_end_at)->endOfDay();

        $query = static::where('active', true)
            ->where('location', $this->location)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Kiểm tra nếu banner mới nằm trong khoảng thời gian của banner hiện có
                    $q->where('display_at', '<=', $startTime)
                        ->where('display_end_at', '>=', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Kiểm tra nếu banner mới kết thúc trong khoảng thời gian của banner hiện có
                    $q->where('display_at', '<=', $endTime)
                        ->where('display_end_at', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Kiểm tra nếu banner mới bao trùm hoàn toàn banner hiện có
                    $q->where('display_at', '>=', $startTime)
                        ->where('display_end_at', '<=', $endTime);
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