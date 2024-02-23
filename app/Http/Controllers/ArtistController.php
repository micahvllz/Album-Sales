<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    /**
     * Display a listing of the artists.
     */
    public function index()
    {
        $artists = Artist::all();

        if ($artists->isEmpty()) {
            return response()->json([
                'message' => 'No artists found.',
                'data' => $artists
            ], 200);
        }

        return response()->json($artists, 200);
    }

    /**
     * Store a newly created artist in database.
     */
    public function store(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        // Validate request data
        $validated = $request->validate([
            'code' => 'required|integer|unique:artists,code',
            'name' => 'required|string|max:255'
        ]);

        $artist = Artist::create($validated);

        return response()->json($artist, 201);
    }

    /**
     * Display the specified artist.
     */
    public function show(string $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json(['error' => 'Artist not found'], 404);
        }

        return response()->json($artist, 200);
    }

    /**
     * Update the specified artist in database.
     */
    public function update(Request $request, string $id)
    {
        $request->headers->set('Accept', 'application/json');

        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json(['error' => 'Artist not found'], 404);
        }

        // Validate request data
        $validated = $request->validate([
            'code' => 'integer|unique:artists,code',
            'name' => 'string|max:255'
        ]);

        $artist->update($validated);

        return response()->json($artist, 200);
    }

    /**
     * Remove the specified artist from database.
     */
    public function destroy(string $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json(['error' => 'Artist not found'], 404);
        }

        $artist->delete();

        return response()->json(['message' => 'Artist deleted'], 200);
    }
}
