<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DogBreed;

class DogBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            [
                'breed_name' => 'Golden Retriever',
                'image_path' => 'GoldenRetriever.jpg',
                'bark_characteristics' => 'High pitch, soft tone',
                'response_voice_style' => 'Calm, reassuring',
                'frequency' => 'Frequent',
                'analysis_count' => 35,
            ],
            [
                'breed_name' => 'German Shepherd',
                'image_path' => 'GermanShepherd.jpg',
                'bark_characteristics' => 'Low pitch, sharp tone',
                'response_voice_style' => 'Authoritative, clear',
                'frequency' => 'Moderate',
                'analysis_count' => 28,
            ],
            [
                'breed_name' => 'Poodle',
                'image_path' => 'Poodle.jpg',
                'bark_characteristics' => 'Medium pitch, melodic tone',
                'response_voice_style' => 'Friendly, playful',
                'frequency' => 'Frequent',
                'analysis_count' => 22,
            ],
            [
                'breed_name' => 'Bulldog',
                'image_path' => 'BullDog.jpg',
                'bark_characteristics' => 'Low pitch, gruff tone',
                'response_voice_style' => 'Firm, direct',
                'frequency' => 'Infrequent',
                'analysis_count' => 10,
            ],
            [
                'breed_name' => 'Beagle',
                'image_path' => 'Beagle.jpg',
                'bark_characteristics' => 'High pitch, urgent tone',
                'response_voice_style' => 'Alert, responsive',
                'frequency' => 'Frequent',
                'analysis_count' => 18,
            ],
            [
                'breed_name' => 'Labrador Retriever',
                'image_path' => 'LabradorRetriever.jpg',
                'bark_characteristics' => 'Medium pitch, friendly tone',
                'response_voice_style' => 'Energetic, cheerful',
                'frequency' => 'Frequent',
                'analysis_count' => 15,
            ],
            [
                'breed_name' => 'French Bulldog',
                'image_path' => 'FrenchBulldog.jpg',
                'bark_characteristics' => 'Low pitch, gentle tone',
                'response_voice_style' => 'Playful, affectionate',
                'frequency' => 'Moderate',
                'analysis_count' => 12,
            ],
            [
                'breed_name' => 'Siberian Husky',
                'image_path' => 'SiberianHusky.jpg',
                'bark_characteristics' => 'Medium pitch, howling tone',
                'response_voice_style' => 'Vocal, expressive',
                'frequency' => 'Frequent',
                'analysis_count' => 8,
            ],
            [
                'breed_name' => 'Rottweiler',
                'image_path' => 'Rottweiler.jpg',
                'bark_characteristics' => 'Low pitch, deep tone',
                'response_voice_style' => 'Protective, confident',
                'frequency' => 'Moderate',
                'analysis_count' => 7,
            ],
            [
                'breed_name' => 'Border Collie',
                'image_path' => 'BorderCollie.jpg',
                'bark_characteristics' => 'Medium pitch, alert tone',
                'response_voice_style' => 'Intelligent, responsive',
                'frequency' => 'Frequent',
                'analysis_count' => 6,
            ],
            [
                'breed_name' => 'Boxer',
                'image_path' => 'Boxer.jpg',
                'bark_characteristics' => 'Medium pitch, energetic tone',
                'response_voice_style' => 'Playful, energetic',
                'frequency' => 'Moderate',
                'analysis_count' => 5,
            ],
            [
                'breed_name' => 'Chihuahua',
                'image_path' => 'Chihuahua.jpg',
                'bark_characteristics' => 'High pitch, sharp tone',
                'response_voice_style' => 'Alert, vocal',
                'frequency' => 'Frequent',
                'analysis_count' => 4,
            ],
            [
                'breed_name' => 'Cocker Spaniel',
                'image_path' => 'CockerSpaniel.jpg',
                'bark_characteristics' => 'Medium pitch, melodic tone',
                'response_voice_style' => 'Friendly, gentle',
                'frequency' => 'Moderate',
                'analysis_count' => 3,
            ],
            [
                'breed_name' => 'Dachshund',
                'image_path' => 'Dachshund.jpg',
                'bark_characteristics' => 'High pitch, persistent tone',
                'response_voice_style' => 'Bold, determined',
                'frequency' => 'Frequent',
                'analysis_count' => 3,
            ],
            [
                'breed_name' => 'Maltese',
                'image_path' => 'Maltese.jpg',
                'bark_characteristics' => 'High pitch, soft tone',
                'response_voice_style' => 'Gentle, affectionate',
                'frequency' => 'Moderate',
                'analysis_count' => 2,
            ],
            [
                'breed_name' => 'Pug',
                'image_path' => 'Pug.jpg',
                'bark_characteristics' => 'Low pitch, snorting tone',
                'response_voice_style' => 'Playful, charming',
                'frequency' => 'Moderate',
                'analysis_count' => 2,
            ],
            [
                'breed_name' => 'Shih Tzu',
                'image_path' => 'ShihTzu.jpg',
                'bark_characteristics' => 'Medium pitch, gentle tone',
                'response_voice_style' => 'Calm, friendly',
                'frequency' => 'Infrequent',
                'analysis_count' => 2,
            ],
            [
                'breed_name' => 'Yorkshire Terrier',
                'image_path' => 'YorkshireTerrier.jpg',
                'bark_characteristics' => 'High pitch, alert tone',
                'response_voice_style' => 'Bold, confident',
                'frequency' => 'Frequent',
                'analysis_count' => 2,
            ],
        ];

        foreach ($breeds as $breed) {
            DogBreed::updateOrCreate(
                ['breed_name' => $breed['breed_name']],
                $breed
            );
        }

        $this->command->info('✅ Dog breeds seeded successfully with images!');
    }
}
