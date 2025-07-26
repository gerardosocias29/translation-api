<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Translation::factory()->count(env('TRANSLATION_SEED_COUNT', 120000))->create([
            'locale' => 'en',
            'tag' => 'web',
        ]);
    }
}
