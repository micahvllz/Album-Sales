<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use League\Csv\Reader;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(storage_path('app/album-sales.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            Artist::firstOrCreate([
                'name' => $record['Artist'],
            ], [
                'code' => Artist::factory()->make()->code,
            ]);
        }
    }
}
