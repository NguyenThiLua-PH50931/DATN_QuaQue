<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Region;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:regions,name',
        ]);

        Region::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->back()->with('success', 'Đã thêm vùng miền mới!');
    }
}
