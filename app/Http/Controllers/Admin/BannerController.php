<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Lọc theo ngày bắt đầu hiển thị
        if ($request->has('start_date') && $request->input('start_date')) {
            $query->whereDate('display_at', '>=', $request->input('start_date'));
        }

        // Lọc theo ngày kết thúc hiển thị
        if ($request->has('end_date') && $request->input('end_date')) {
            $query->whereDate('display_end_at', '<=', $request->input('end_date'));
        }

        $banners = $query->get();
        return view('backend.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable|url|max:255',
            'display_at' => 'nullable|date',
            'display_end_at' => 'nullable|date|after_or_equal:display_at',
            'location' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        $banner = new Banner([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'active' => $request->boolean('active'),
            'display_at' => $request->display_at,
            'display_end_at' => $request->display_end_at,
            'location' => $request->location,
        ]);

        if ($banner->active && $banner->hasOverlappingActiveBanner()) {
            return back()->withErrors(['active' => 'Không thể kích hoạt banner này vì đã có banner khác đang hoạt động trong khoảng thời gian này tại vị trí này.'])->withInput();
        }

        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được tạo mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable|url|max:255',
            'display_at' => 'nullable|date',
            'display_end_at' => 'nullable|date|after_or_equal:display_at',
            'location' => 'nullable|string|max:255',
        ]);

        $imagePath = $banner->image;
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        $banner->fill([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'active' => $request->boolean('active'),
            'display_at' => $request->display_at,
            'display_end_at' => $request->display_end_at,
            'location' => $request->location,
        ]);

        if ($banner->active && $banner->hasOverlappingActiveBanner()) {
            return back()->withErrors(['active' => 'Không thể kích hoạt banner này vì đã có banner khác đang hoạt động trong khoảng thời gian này tại vị trí này.'])->withInput();
        }

        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(string $id)
    {
        $banner = Banner::findOrFail($id);
        $now = Carbon::now();

        // Kiểm tra nếu banner đang hoạt động và thời gian hiển thị chưa kết thúc
        // Hoặc nếu banner có display_end_at nhưng thời gian hiện tại vẫn <= display_end_at (cuối ngày đó)
        if ($banner->active && $banner->display_end_at && $now->lessThanOrEqualTo(Carbon::parse($banner->display_end_at)->endOfDay())) {
            return redirect()->route('admin.banners.index')->with('error', 'Không thể xóa banner này vì nó đang trong quá trình hiển thị.');
        }

        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được chuyển vào thùng rác.');
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->forceDelete();
        return redirect()->route('admin.banners.trashed')->with('success', 'Đã xoá vĩnh viễn Banner.');
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();
        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được khôi phục thành công.');
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        $banners = Banner::onlyTrashed()->get();
        return view('backend.banners.trashed', compact('banners'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        $bannersToDelete = [];
        $notDeletedBannerTitles = [];
        $now = Carbon::now();

        foreach ($ids as $id) {
            $banner = Banner::find($id);
            if ($banner) {
                // Kiểm tra nếu banner đang hoạt động và thời gian hiển thị chưa kết thúc
                if ($banner->active && $banner->display_end_at && $now->lessThanOrEqualTo(Carbon::parse($banner->display_end_at)->endOfDay())) {
                    $notDeletedBannerTitles[] = $banner->title;
                } else {
                    $bannersToDelete[] = $id;
                }
            }
        }

        if (!empty($bannersToDelete)) {
            Banner::whereIn('id', $bannersToDelete)->delete();
        }

        $deletedCount = count($bannersToDelete);
        $notDeletedCount = count($notDeletedBannerTitles);

        $message = '';
        if ($deletedCount > 0) {
            $message .= 'Đã xóa mềm ' . $deletedCount . ' banner thành công.';
        }
        if ($notDeletedCount > 0) {
            if ($deletedCount > 0) {
                $message .= ' Tuy nhiên, không thể xóa ' . $notDeletedCount . ' banner đang hiển thị: ' . implode(', ', $notDeletedBannerTitles) . '.';
            } else {
                $message .= 'Không thể xóa ' . $notDeletedCount . ' banner đang hiển thị: ' . implode(', ', $notDeletedBannerTitles) . '.';
            }
        }

        $status = 'success';
        if ($deletedCount == 0 && $notDeletedCount > 0) {
            $status = 'error';
        } elseif ($deletedCount > 0 && $notDeletedCount > 0) {
            $status = 'warning';
        }

        return response()->json(['message' => $message, 'status' => $status, 'deletedCount' => $deletedCount], 200);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        Banner::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['message' => 'Đã khôi phục các banner đã chọn thành công.'], 200);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        $deletedCount = 0;
        $notDeletedTitles = [];

        $banners = Banner::onlyTrashed()->whereIn('id', $ids)->get();

        foreach ($banners as $banner) {
            // Tương tự, nếu có liên kết sản phẩm với banner bị xóa mềm, có thể kiểm tra ở đây
            // Hiện tại, không có liên kết trực tiếp giữa banner và sản phẩm, nên sẽ xóa vĩnh viễn.
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->forceDelete();
            $deletedCount++;
        }

        return response()->json(['message' => 'Đã xóa vĩnh viễn ' . $deletedCount . ' banner đã chọn thành công.', 'status' => 'success', 'deletedCount' => $deletedCount], 200);
    }
}
