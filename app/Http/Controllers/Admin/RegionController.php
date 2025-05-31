<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BE\Region;
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
        return view('backend.regions.trashed', compact('regions'));
    }

    public function create()
    {
        return view('backend.regions.create');
    }

    public function edit($id)
    {
        $region = Region::findOrFail($id);
        return view('backend.regions.edit', compact('region'));
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
        $region->delete();

        // Flash a success message and redirect
        session()->flash('success', 'Xóa mềm vùng miền thành công!');
        return redirect()->route('admin.regions.index');
    }

    /**
     * Hard delete the specified region.
     */
    public function forceDelete($id)
    {
        $region = Region::withTrashed()->findOrFail($id);
        $region->forceDelete();

        // Still return JSON for AJAX handling on trashed page
        return response()->json(['message' => 'Xóa cứng vùng miền thành công'], 200);
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
}
