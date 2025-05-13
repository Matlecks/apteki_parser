<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);

        $pharmacies = Pharmacy::query()
            ->paginate(100, ['*'], 'page', $page);

        return response()->json([
            'data' => $pharmacies->items(),
            'meta' => [
                'current_page' => $pharmacies->currentPage(),
                'per_page' => $pharmacies->perPage(),
                'total' => $pharmacies->total(),
                'last_page' => $pharmacies->lastPage(),
                'prev_page_url' => $pharmacies->previousPageUrl(),
                'next_page_url' => $pharmacies->nextPageUrl(),
                'path' => $pharmacies->path(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
