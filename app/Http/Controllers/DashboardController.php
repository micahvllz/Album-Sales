<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;

class DashboardController extends Controller
{
    public function totalAlbumsSoldPerArtist()
    {
        $data = Album::select('artist_id')
            ->selectRaw('SUM(sales) as total_sales')
            ->groupBy('artist_id')
            ->with('artist:id,name')
            ->get();

        return response()->json(['data' => $data], 200);
    }

    public function combinedAlbumSalesPerArtist()
    {
        $data = Artist::withSum('albums', 'sales')
            ->get(['id', 'name', 'albums_sum_sales']);

        return response()->json(['data' => $data], 200);
    }

    public function topSellingArtist()
    {
        $data = Artist::withSum('albums', 'sales')
            ->orderByDesc('albums_sum_sales')
            ->first(['id', 'name', 'albums_sum_sales']);

        if (!$data) {
            return response()->json(['message' => 'No artists found'], 404);
        }

        return response()->json(['data' => $data], 200);
    }

    public function albumsByArtist(Request $request)
    {
        $artistName = $request->input('artist_name');
        $data = Artist::where('name', 'like', "%{$artistName}%")
            ->with('albums')
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No albums found for the specified artist'], 404);
        }

        return response()->json(['data' => $data], 200);
    }
}
