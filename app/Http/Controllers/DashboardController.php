<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class DashboardController extends Controller
{
    public function totalAlbumsSoldPerArtist()
    {
        $data = Artist::withCount('albums')
            ->get()
            ->map(function ($artist) {
                return [
                    'artist' => $artist->name,
                    'total_albums_sold' => $artist->albums_count
                ];
            });

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No albums available for any artist.'], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function combinedAlbumSalesPerArtist()
    {
        $data = Artist::withSum('albums', 'sales')
            ->get()
            ->map(function ($artist) {
                return [
                    'artist' => $artist->name,
                    'combined_album_sales' => $artist->albums_sum_sales
                ];
            });

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No album sales data found for any artist.'], 200);
        }

        return response()->json(['data' => $data], 200);
    }

    public function topSellingArtist()
    {
        $data = Artist::withSum('albums', 'sales')
            ->orderByDesc('albums_sum_sales')
            ->first();

        if (!$data) {
            return response()->json(['message' => 'No sales data available to determine the top selling artist.'], 200);
        }

        $result = [
            'artist' => $data->name,
            'combined_album_sales' => $data->albums_sum_sales
        ];

        return response()->json(['data' => $result], 200);
    }

    public function albumsByArtist(Request $request)
    {
        $artistName = $request->input('artist_name');

        // Check if the artist exists
        $artist = Artist::where('name', 'like', "%{$artistName}%")->first();

        if (!$artist) {
            return response()->json(['message' => "Artist '{$artistName}' not found."], 404);
        }

        if ($artist->albums->isEmpty()) {
            return response()->json(['message' => "No albums found for artist '{$artist->name}'."], 200);
        }

        $albums = $artist->albums->map(function ($album) {
            return [
                'year' => $album->year,
                'name' => $album->name,
                'sales' => $album->sales,
                'cover_image' => $album->cover_image
            ];
        });

        $result = [
            'artist' => $artist->name,
            'albums' => $albums
        ];

        return response()->json(['data' => $result], 200);
    }
}
