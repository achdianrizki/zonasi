<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class RegencyApiConroller extends Controller
{
    // public function index()
    // {
    //     $response = Http::withOptions([
    //         'verify' => false,
    //     ])->withHeaders([
    //         'x-api-co-id' => env('API_CO_ID_KEY'),
    //     ])->get('https://use.api.co.id/regional/indonesia/regencies/3204');

    //     if (!$response->successful()) {
    //         abort(500, 'Gagal mengambil data Kecamatan');
    //     }

    //     $districts = collect($response->json('data.districts') ?? [])
    //         ->sortBy('name')
    //         ->values()
    //         ->all();

    //     return view('admin.regency.index', compact('districts'));
    // }

    public function index()
    {
        $districtCodes = [
            '320416',
            '320432',
            '320413',
            '320408',
            '320444',
            '320425',
            '320427',
            '320407',
            '320405',
            '320417',
            '320406',
            '320429',
            '320439',
            '320412',
            '320436',
            '320411',
            '320431',
            '320446',
            '320433',
            '320410',
            '320409',
            '320426',
            '320430',
            '320414',
            '320415',
            '320435',
            '320438',
            '320440',
            '320428',
            '320434',
            '320437',
        ];

        $villages = collect();

        foreach ($districtCodes as $districtCode) {
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'x-api-co-id' => env('API_CO_ID_KEY'),
            ])->get("https://use.api.co.id/regional/indonesia/districts/{$districtCode}");

            if ($response->successful()) {
                $data = collect($response->json('data.villages') ?? [])
                    ->map(function ($village) use ($districtCode) {
                        return [
                            'district_code' => $districtCode,
                            'village_code'  => $village['code'],
                            'village_name'  => $village['name'],
                        ];
                    });

                $villages = $villages->merge($data);
            }
        }

        return response()->json([
            'success' => true,
            'total'   => $villages->count(),
            'data'    => $villages->sortBy('village_name')->values(),
        ]);
    }
}
