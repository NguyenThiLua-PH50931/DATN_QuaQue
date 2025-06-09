<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'active' => 'boolean',
            'display_at' => 'nullable|date',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'active' => $request->has('active'),
            'display_at' => $request->display_at,
        ]);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'active' => 'boolean',
            'display_at' => 'nullable|date',
        ]);

        $imagePath = $banner->image;
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'active' => $request->has('active'),
            'display_at' => $request->display_at,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete(string $id)
    {
        $banner = Banner::findOrFail($id);
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
}
