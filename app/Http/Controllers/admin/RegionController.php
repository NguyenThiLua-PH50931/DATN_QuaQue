<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Region;
use App\Models\admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    /**
     * Display a listing of the regions.
     */
    public function index(Request $request)
    {
        $query = Region::query();

        // Add simple search logic
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Only fetch non-trashed items for the index page
        $regions = $query->paginate(10); // Using pagination

        // Removed AJAX check as we are using standard views
        // if ($request->ajax()) {
        //     return response()->json(['regions' => $regions], 200);
        // }

        return view('backend.regions.index', compact('regions'));
    }

    /**
     * Display a listing of soft-deleted regions.
     */
    public function trashed()
    {
        $regions = Region::onlyTrashed()->get();

        // Tính toán thời gian tự động xóa cho mỗi region
        foreach ($regions as $region) {
            $deletedAt = \Carbon\Carbon::parse($region->deleted_at);
            $autoDeleteAt = $deletedAt->copy()->addDays(30);
            $now = \Carbon\Carbon::now();
            $region->days_until_auto_delete = $now->diffInDays($autoDeleteAt, false);
            $region->auto_delete_at = $autoDeleteAt;
        }

        return view('backend.regions.trashed', compact('regions'));
    }

    /**
     * Kiểm tra trạng thái tự động xóa của region
     */
    public function checkAutoDeleteStatus()
    {
        $trashedRegions = Region::onlyTrashed()->get();
        $autoDeleteStats = [
            'total' => $trashedRegions->count(),
            'will_be_deleted_soon' => 0,
            'days_until_auto_delete' => []
        ];

        foreach ($trashedRegions as $region) {
            $deletedAt = \Carbon\Carbon::parse($region->deleted_at);
            $autoDeleteAt = $deletedAt->copy()->addDays(30);
            $now = \Carbon\Carbon::now();
            $daysLeft = $now->diffInDays($autoDeleteAt, false);

            if ($daysLeft <= 7) {
                $autoDeleteStats['will_be_deleted_soon']++;
            }

            $autoDeleteStats['days_until_auto_delete'][] = [
                'region_id' => $region->id,
                'name' => $region->name,
                'days_left' => $daysLeft,
                'auto_delete_at' => $autoDeleteAt->format('Y-m-d H:i:s')
            ];
        }

        return response()->json($autoDeleteStats);
    }



    /**
     * Store a newly created region in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:regions',
        ]);

        if ($validator->fails()) {
            // Changed to redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $region = Region::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Flash a success message and redirect
        session()->flash('success', 'Thêm vùng miền thành công!');
        return redirect()->route('admin.regions.index');
    }

    /**
     * Update the specified region in storage.
     */
    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:regions,name,' . $id, // Added unique validation exclusion
        ]);

        if ($validator->fails()) {
            // Changed to redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $region->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Flash a success message and redirect
        session()->flash('success', 'Cập nhật vùng miền thành công!');
        return redirect()->route('admin.regions.index');
    }

    /**
     * Soft delete the specified region.
     */
    public function softDelete($id)
    {
        $region = Region::findOrFail($id);

        // Kiểm tra xem vùng miền có liên kết với bất kỳ sản phẩm nào không
        if (Product::where('region_id', $id)->exists()) {
            session()->flash('error', 'Không thể xóa vùng miền này vì đang có sản phẩm liên kết.');
            return redirect()->route('admin.regions.index');
        }

        $region->delete();

        // Flash a success message and redirect
        session()->flash('success', 'Xóa vùng miền thành công!');
        return redirect()->route('admin.regions.index');
    }

    /**
     * Hard delete the specified region.
     */
    public function forceDelete($id)
    {
        $region = Region::withTrashed()->findOrFail($id);

        // Kiểm tra xem vùng miền có liên kết với bất kỳ sản phẩm nào không
        if (Product::where('region_id', $id)->exists()) {
            return response()->json(['message' => 'Không thể xóa vĩnh viễn vùng miền này vì đang có sản phẩm liên kết.', 'status' => 'error'], 400);
        }

        $region->forceDelete();

        // Still return JSON for AJAX handling on trashed page
        return response()->json(['message' => 'Xóa vĩnh viễn vùng miền thành công', 'status' => 'success'], 200);
    }

    /**
     * Restore the soft-deleted region.
     */
    public function restore($id)
    {
        $region = Region::withTrashed()->findOrFail($id);
        $region->restore();

        // Flash a success message and redirect
        session()->flash('success', 'Khôi phục vùng miền thành công!');
        return redirect()->route('admin.regions.index'); // Redirect to index page after restore
    }

    public function storeQuick(Request $request)
    {

        // Validator với thông báo tiếng Việt, bảng regions, field name
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:regions,name',
        ], [
            'name.required' => 'Tên vùng miền bắt buộc',
            'name.unique'   => 'Tên vùng miền đã tồn tại, vui lòng chọn tên khác',
            'name.max'      => 'Tên vùng miền không được vượt quá 100 ký tự',

        ]);

        // Nếu validate lỗi
        if ($validator->fails()) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(), // { name: [...] }
                ], 422);
            }
            // Nếu không phải ajax thì redirect lại (backup)

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tạo vùng miền mới
        $region = Region::create([
            'name' => $request->name,

            'slug' => \Str::slug($request->name),
        ]);

        // Trả về JSON nếu là ajax
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vùng miền mới!',
                'region'  => [
                    'id'   => $region->id,
                    'name' => $region->name,
                    'slug' => $region->slug,
                ],
            ]);
        }

        // Nếu không phải ajax thì redirect lại (hiếm gặp)
        return redirect()->back()->with('success', 'Đã thêm vùng miền mới!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        $deletedCount = 0;
        $notDeletedNames = [];

        foreach ($ids as $id) {
            $region = Region::find($id);
            if ($region) {
                if (Product::where('region_id', $id)->exists()) {
                    $notDeletedNames[] = $region->name; // Lưu tên vùng miền không thể xóa
                } else {
                    $region->delete(); // Xóa mềm vùng miền
                    $deletedCount++;
                }
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= 'Đã xóa mềm ' . $deletedCount . ' vùng miền thành công.';
        }

        if (count($notDeletedNames) > 0) {
            if ($deletedCount > 0) {
                $message .= ' Tuy nhiên, ';
            }
            $message .= 'các vùng miền sau không thể xóa do có sản phẩm liên kết: ' . implode(', ', $notDeletedNames) . '.';
            return response()->json(['message' => $message, 'status' => 'warning', 'deletedCount' => $deletedCount, 'notDeletedNames' => $notDeletedNames], 200);
        }

        return response()->json(['message' => $message, 'status' => 'success', 'deletedCount' => $deletedCount], 200);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        Region::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['message' => 'Đã khôi phục các vùng miền đã chọn thành công.'], 200);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // Đảm bảo $ids là một mảng
        }

        $deletedCount = 0;
        $notDeletedNames = [];

        foreach ($ids as $id) {
            $region = Region::withTrashed()->find($id);
            if ($region) {
                if (Product::where('region_id', $id)->exists()) {
                    $notDeletedNames[] = $region->name; // Lưu tên vùng miền không thể xóa
                } else {
                    $region->forceDelete(); // Xóa cứng vùng miền
                    $deletedCount++;
                }
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= 'Đã xóa vĩnh viễn ' . $deletedCount . ' vùng miền thành công.';
        }

        if (count($notDeletedNames) > 0) {
            if ($deletedCount > 0) {
                $message .= ' Tuy nhiên, ';
            }
            $message .= 'các vùng miền sau không thể xóa do có sản phẩm liên kết: ' . implode(', ', $notDeletedNames) . '.';
            return response()->json(['message' => $message, 'status' => 'warning', 'deletedCount' => $deletedCount, 'notDeletedNames' => $notDeletedNames], 200);
        }

        return response()->json(['message' => $message, 'status' => 'success', 'deletedCount' => $deletedCount], 200);
    }
}
