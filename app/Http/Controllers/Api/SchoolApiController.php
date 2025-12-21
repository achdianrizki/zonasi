<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolApiController extends Controller
{
    public function index()
    {
        $schools = School::with('district', 'village')
            ->get()
            ->map(function ($s) {
                return [
                    'id'            => $s->id,
                    'name'          => $s->name,
                    'npsn'          => $s->npsn,
                    'address'       => $s->address,
                    'district_name' => $s->district->name ?? '-',
                    'village_name'  => $s->village->name ?? '-',
                    'latitude'      => $s->latitude,
                    'longitude'     => $s->longitude,
                ];
            });

        return response()->json(['data' => $schools]);
    }

    public function show($id)
    {
        $school = School::with('district', 'village')->find($id);

        if (! $school) {
            return response()->json([
                'message' => 'Sekolah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'data' => $school
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'npsn'        => 'required|string|max:20|unique:schools,npsn',
            'address'     => 'nullable|string',
            'district_id' => 'required|exists:districts,id',
            'village_id'  => 'required|exists:villages,id',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);

        $school = School::create($validated);

        return response()->json([
            'message' => 'Sekolah berhasil ditambahkan',
            'data'    => $school
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $school = School::find($id);

        if (! $school) {
            return response()->json([
                'message' => 'Sekolah tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'npsn'        => [
                'required',
                'string',
                'max:20',
                Rule::unique('schools', 'npsn')->ignore($school->id),
            ],
            'address'     => 'nullable|string',
            'district_id' => 'required|exists:districts,id',
            'village_id'  => 'required|exists:villages,id',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);

        $school->update($validated);

        return response()->json([
            'message' => 'Sekolah berhasil diperbarui',
            'data'    => $school
        ]);
    }

    public function destroy($id)
    {
        $school = School::find($id);

        if (! $school) {
            return response()->json([
                'message' => 'Sekolah tidak ditemukan'
            ], 404);
        }

        $school->delete();

        return response()->json([
            'message' => 'Sekolah berhasil dihapus'
        ]);
    }
}
