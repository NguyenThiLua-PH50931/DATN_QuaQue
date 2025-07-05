<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    // Lấy danh sách tỉnh
    public function provinces()
    {
        $json = Storage::get('public/vietnamAddress.json');
        $data = json_decode($json, true);

        $provinces = collect($data)->map(function($item){
            return [
                'name' => $item['Name'],
                'code' => $item['Id'] ?? null,
            ];
        });

        return response()->json($provinces);
    }

    // Lấy huyện theo tỉnh
    public function districts(Request $request)
    {
        $provinceName = $request->input('province');

        $json = Storage::get('public/vietnamAddress.json');
        $data = json_decode($json, true);

        $province = collect($data)->firstWhere('Name', $provinceName);

        if (!$province) return response()->json([], 404);

        $districts = collect($province['Districts'])->map(function($item){
            return [
                'name' => $item['Name'],
                'code' => $item['Id'] ?? null,
            ];
        });

        return response()->json($districts);
    }

    // Lấy xã theo huyện
    public function wards(Request $request)
    {
        $provinceName = $request->input('province');
        $districtName = $request->input('district');

        $json = Storage::get('public/vietnamAddress.json');
        $data = json_decode($json, true);

        $province = collect($data)->firstWhere('Name', $provinceName);
        if (!$province) return response()->json([], 404);

        $district = collect($province['Districts'])->firstWhere('Name', $districtName);
        if (!$district) return response()->json([], 404);

        $wards = collect($district['Wards'])->map(function($item){
            return [
                'name' => $item['Name'],
                'code' => $item['Id'] ?? null,
            ];
        });

        return response()->json($wards);
    }
    
}

