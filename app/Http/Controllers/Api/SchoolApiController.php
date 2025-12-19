<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SchoolApiController extends Controller
{
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

        $allSchools = collect();

        foreach ($districtCodes as $districtCode) {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'x-api-co-id' => env('API_CO_ID_KEY'),
                ])->get('https://use.api.co.id/regional/indonesia/schools', [
                    'district_code' => $districtCode,
                    'grade'         => 'SMA',
                    'status'        => 'N',
                    'page'          => 1,
                    'size'          => 100,
                ]);

            if ($response->successful()) {
                $schools = collect($response->json('data') ?? []);
                $allSchools = $allSchools->merge($schools);
            }
        }

        $schools = $allSchools
            ->unique('npsn', '320415') // cegah duplikasi
            ->sortBy('name')
            ->values()
            ->all();

        return view('admin.schools.index', compact('schools'));
    }
}
