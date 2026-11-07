<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportedLanguage;

class SupportedLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'language' => 'English',
                'code' => 'en',
                'status' => true,
                'translation_file' => 'en.json',
                'usage_count' => 850,
            ],
            [
                'language' => 'Spanish',
                'code' => 'es',
                'status' => true,
                'translation_file' => 'es.json',
                'usage_count' => 620,
            ],
            [
                'language' => 'French',
                'code' => 'fr',
                'status' => false,
                'translation_file' => null,
                'usage_count' => 180,
            ],
            [
                'language' => 'German',
                'code' => 'de',
                'status' => false,
                'translation_file' => null,
                'usage_count' => 120,
            ],
            [
                'language' => 'Chinese',
                'code' => 'zh',
                'status' => false,
                'translation_file' => null,
                'usage_count' => 90,
            ],
        ];

        foreach ($languages as $language) {
            SupportedLanguage::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }

        $this->command->info('✅ Supported languages seeded successfully!');
    }
}
