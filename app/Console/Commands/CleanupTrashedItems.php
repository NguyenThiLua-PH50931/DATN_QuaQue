<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Banner;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Region;
use App\Models\Admin\Attribute;
use App\Models\Admin\Comment;
use App\Models\Admin\Blog;
use App\Models\Admin\DiscountCode;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTrashedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trashed:cleanup-all {--days=30 : Số ngày trước khi xóa vĩnh viễn} {--type=all : Loại item cần xóa (all, banners, categories, products, regions, attributes, comments, blogs, coupons)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động xóa vĩnh viễn tất cả các item đã bị xóa mềm sau số ngày chỉ định';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $type = $this->option('type');

        $this->info("Bắt đầu dọn dẹp các item đã xóa mềm từ {$days} ngày trước...");
        if ($type !== 'all') {
            $this->info("Chỉ xóa loại: {$type}");
        }

        $cutoffDate = Carbon::now()->subDays($days);
        $totalDeleted = 0;

        // Xử lý theo loại được chọn
        switch ($type) {
            case 'banners':
                $totalDeleted += $this->cleanupBanners($cutoffDate);
                break;
            case 'categories':
                $totalDeleted += $this->cleanupCategories($cutoffDate);
                break;
            case 'products':
                $totalDeleted += $this->cleanupProducts($cutoffDate);
                break;
            case 'regions':
                $totalDeleted += $this->cleanupRegions($cutoffDate);
                break;
            case 'attributes':
                $totalDeleted += $this->cleanupAttributes($cutoffDate);
                break;
            case 'comments':
                $totalDeleted += $this->cleanupComments($cutoffDate);
                break;
            case 'blogs':
                $totalDeleted += $this->cleanupBlogs($cutoffDate);
                break;
            case 'coupons':
                $totalDeleted += $this->cleanupCoupons($cutoffDate);
                break;
            case 'all':
            default:
                // Xóa tất cả
                $totalDeleted += $this->cleanupBanners($cutoffDate);
                $totalDeleted += $this->cleanupCategories($cutoffDate);
                $totalDeleted += $this->cleanupProducts($cutoffDate);
                $totalDeleted += $this->cleanupRegions($cutoffDate);
                $totalDeleted += $this->cleanupAttributes($cutoffDate);
                $totalDeleted += $this->cleanupComments($cutoffDate);
                $totalDeleted += $this->cleanupBlogs($cutoffDate);
                $totalDeleted += $this->cleanupCoupons($cutoffDate);
                break;
        }

        $this->info("Hoàn thành! Đã xóa vĩnh viễn tổng cộng {$totalDeleted} item.");
        return 0;
    }

    private function cleanupBanners($cutoffDate)
    {
        $trashedBanners = Banner::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedBanners as $banner) {
            try {
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $banner->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn banner: {$banner->title} (ID: {$banner->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa banner {$banner->title} (ID: {$banner->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} banner.");
        }
        return $deletedCount;
    }

    private function cleanupCategories($cutoffDate)
    {
        $trashedCategories = Category::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedCategories as $category) {
            try {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $category->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn category: {$category->name} (ID: {$category->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa category {$category->name} (ID: {$category->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} category.");
        }
        return $deletedCount;
    }

    private function cleanupProducts($cutoffDate)
    {
        $trashedProducts = Product::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedProducts as $product) {
            try {
                // Xóa ảnh đại diện
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                // Xóa ảnh mô tả
                foreach ($product->product_images as $image) {
                    if (Storage::disk('public')->exists($image->image_url)) {
                        Storage::disk('public')->delete($image->image_url);
                    }
                    $image->delete();
                }

                // Xóa biến thể và ảnh biến thể
                foreach ($product->variants()->withTrashed()->get() as $variant) {
                    if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->attributeValues()->detach();
                    $variant->forceDelete();
                }

                // Xóa bình luận và phản hồi
                foreach ($product->comments as $comment) {
                    $comment->replies()->delete();
                    $comment->delete();
                }

                $product->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn product: {$product->name} (ID: {$product->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa product {$product->name} (ID: {$product->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} product.");
        }
        return $deletedCount;
    }

    private function cleanupRegions($cutoffDate)
    {
        $trashedRegions = Region::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedRegions as $region) {
            try {
                $region->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn region: {$region->name} (ID: {$region->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa region {$region->name} (ID: {$region->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} region.");
        }
        return $deletedCount;
    }

    private function cleanupAttributes($cutoffDate)
    {
        $trashedAttributes = Attribute::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedAttributes as $attribute) {
            try {
                $attribute->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn attribute: {$attribute->name} (ID: {$attribute->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa attribute {$attribute->name} (ID: {$attribute->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} attribute.");
        }
        return $deletedCount;
    }

    private function cleanupComments($cutoffDate)
    {
        $trashedComments = Comment::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedComments as $comment) {
            try {
                // Xóa phản hồi
                $comment->replies()->delete();
                $comment->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn comment ID: {$comment->id}");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa comment ID {$comment->id}: " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} comment.");
        }
        return $deletedCount;
    }

    private function cleanupBlogs($cutoffDate)
    {
        $trashedBlogs = Blog::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedBlogs as $blog) {
            try {
                if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
                    unlink(public_path($blog->thumbnail));
                }
                $blog->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn blog: {$blog->title} (ID: {$blog->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa blog {$blog->title} (ID: {$blog->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} blog.");
        }
        return $deletedCount;
    }

    private function cleanupCoupons($cutoffDate)
    {
        $trashedCoupons = DiscountCode::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $deletedCount = 0;
        foreach ($trashedCoupons as $coupon) {
            try {
                $coupon->forceDelete();
                $deletedCount++;
                $this->line("Đã xóa vĩnh viễn coupon: {$coupon->code} (ID: {$coupon->id})");
            } catch (\Exception $e) {
                $this->error("Lỗi khi xóa coupon {$coupon->code} (ID: {$coupon->id}): " . $e->getMessage());
            }
        }

        if ($deletedCount > 0) {
            $this->info("Đã xóa vĩnh viễn {$deletedCount} coupon.");
        }
        return $deletedCount;
    }
}
