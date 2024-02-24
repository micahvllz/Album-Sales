<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the albums.
     */
    public function index()
    {
        $albums = Album::all();

        if ($albums->isEmpty()) {
            return response()->json([
                'message' => 'No albums found.',
                'data' => $albums
            ], 200);
        }

        return response()->json($albums, 200);
    }

    /**
     * Store a newly created album in database.
     */
    public function store(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        // Validate request data
        $validated = $request->validate([
            'year' => 'required|integer|digits:4',
            'name' => 'required|string|max:255',
            'sales' => 'required|decimal:2',
            'artist_id' => 'required|exists:artists,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('album_covers', 'public');
            $validated['cover_image'] = $path;
        }

        $album = Album::create($validated);

        return response()->json($album, 201);
    }

    /**
     * Display the specified album.
     */
    public function show(string $id)
    {
        $album = Album::find($id);

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        return response()->json($album, 200);
    }

    /**
     * Update the specified album in database.
     */
    public function update(Request $request, string $id)
    {
        $request->headers->set('Accept', 'application/json');

        $album = Album::find($id);

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        // Validate request data
        $validated = $request->validate([
            'year' => 'integer|digits:4',
            'name' => 'string|max:255',
            'sales' => 'decimal:2',
            'artist_id' => 'exists:artists,id',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($album->cover_image) {
                Storage::disk('public')->delete($album->cover_image);
            }

            // Store new image
            $path = $request->file('cover_image')->store('album_covers', 'public');
            $validated['cover_image'] = $path;
        }

        $album->update($validated);

        return response()->json($album, 200);
    }

    /**
     * Remove the specified album from database.
     */
    public function destroy(string $id)
    {
        $album = Album::find($id);

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        if ($album->cover_image) {
            Storage::disk('public')->delete($album->cover_image);
        }

        $album->delete();

        return response()->json(['message' => 'Album deleted'], 200);
    }
}
