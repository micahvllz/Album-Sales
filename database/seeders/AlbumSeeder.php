<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\Artist;
use League\Csv\Reader;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(storage_path('app/album-sales.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $artist = Artist::where('name', $record['Artist'])->first();

            if ($artist) {
                Album::firstOrCreate([
                    'artist_id' => $artist->id,
                    'name' => $record['Album'],
                ], [
                    'year' => substr($record['Date Released'], 0, 4),
                    'sales' => $record['2022 Sales'],
                    'cover_image' => Album::factory()->make()->cover_image,
                ]);
            }
        }
    }
}
