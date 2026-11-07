<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmotionResponseMapping;

class EmotionResponseMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mappings = [
            [
                'emotion' => 'Happy',
                'response_phrase' => 'Woof woof! (I\'m so happy to see you!)',
            ],
            [
                'emotion' => 'Sad',
                'response_phrase' => 'Arf arf... (I miss you when you\'re gone.)',
            ],
            [
                'emotion' => 'Angry',
                'response_phrase' => 'Grrr... (I don\'t like that!)',
            ],
            [
                'emotion' => 'Excited',
                'response_phrase' => 'Woof woof woof! (Let\'s play!)',
            ],
            [
                'emotion' => 'Confused',
                'response_phrase' => 'Arf? (What\'s going on?)',
            ],
        ];

        foreach ($mappings as $mapping) {
            EmotionResponseMapping::updateOrCreate(
                ['emotion' => $mapping['emotion']],
                $mapping
            );
        }

        $this->command->info('✅ Emotion response mappings seeded successfully!');
    }
}
