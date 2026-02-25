<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Seed the countries table from the local JSON file.
     * No external API dependency — fully self-contained.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/countries.json');

        if (!file_exists($jsonPath)) {
            $this->command->warn('data-country.json not found. Skipping CountrySeeder.');
            return;
        }

        $countries = json_decode(file_get_contents($jsonPath), true);

        foreach ($countries as $data) {
            $cca3 = $data['cca3'] ?? null;

            if (!$cca3) {
                continue;
            }

            // Flag paths point to our local filesystem (public/img/flags/)
            $pngPath = file_exists(public_path("img/flags/{$cca3}.png"))
                ? "img/flags/{$cca3}.png"
                : null;

            $svgPath = file_exists(public_path("img/flags/{$cca3}.svg"))
                ? "img/flags/{$cca3}.svg"
                : null;

            Country::updateOrCreate(
                ['cca3' => $cca3],
                [
                    'common_name'   => $data['name']['common'],
                    'official_name' => $data['name']['official'],
                    'flag_png_path' => $pngPath,
                    'flag_svg_path' => $svgPath,
                ]
            );
        }

        $this->command->info("Seeded " . Country::count() . " countries from local data.");
    }
}
