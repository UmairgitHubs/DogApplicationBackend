<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Uncomment to run the dashboard data seeder
        $this->call([
        DashboardDataSeeder::class,
            DogBreedSeeder::class,
            EmotionResponseMappingSeeder::class,
            SupportedLanguageSeeder::class,
        ]);
    }
}
