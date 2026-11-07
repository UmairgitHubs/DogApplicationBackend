<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AudioFile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Generating comprehensive dashboard sample data...');

        // Create users if none exist
        if (User::count() === 0) {
            $this->command->info('Creating sample users...');
            $this->createUsers(20);
        }

        // Create audio files
        $this->command->info('Creating sample audio files...');
        $this->createAudioFiles(100);

        $this->command->info('✅ Sample data generated successfully!');
        $this->command->info('📊 Visit /admin/dashboard to see the results');
    }

    private function createUsers($count)
    {
        $firstNames = ['John', 'Jane', 'Mike', 'Sarah', 'David', 'Emily', 'Chris', 'Lisa', 'Tom', 'Amy', 'James', 'Emma', 'Robert', 'Olivia', 'Michael', 'Sophia', 'William', 'Isabella', 'Daniel', 'Mia'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'White', 'Harris'];

        for ($i = 0; $i < $count; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            User::create([
                'username' => strtolower($firstName . $lastName . ($i + 1)),
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName . '.' . $lastName . ($i + 1) . '@example.com'),
                'password' => Hash::make('password'),
                'email_verified' => rand(0, 10) > 1, // 90% verified
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }
    }

    private function createAudioFiles($count)
    {
        $breeds = [
            'Golden Retriever', 'Golden Retriever', 'Golden Retriever',
            'German Shepherd', 'German Shepherd', 'German Shepherd',
            'Labrador Retriever', 'Labrador Retriever', 'Labrador Retriever',
            'French Bulldog', 'French Bulldog',
            'Bulldog', 'Bulldog',
            'Poodle', 'Poodle',
            'Beagle',
            'Rottweiler',
            'Siberian Husky',
            'Border Collie',
            'Chihuahua',
            'Yorkshire Terrier',
        ];

        $emotions = [
            'Happy', 'Happy', 'Happy', 'Happy',
            'Excited', 'Excited', 'Excited',
            'Calm', 'Calm', 'Calm',
            'Playful', 'Playful', 'Playful',
            'Alert', 'Alert',
            'Anxious',
            'Sleepy',
            'Aggressive',
            'Curious',
            'Relaxed'
        ];

        $languages = [
            'English', 'English', 'English', 'English', 'English', 'English',
            'Spanish', 'Spanish', 'Spanish',
            'French', 'French',
            'German', 'German',
            'Italian',
            'Portuguese',
            'Dutch',
            'Japanese',
            'Chinese',
            'Russian'
        ];

        $filenames = [
            'dog_barking_001.mp3',
            'puppy_playtime.wav',
            'dog_whining.m4a',
            'aggressive_bark.mp3',
            'happy_tail_wag.wav',
            'sleepy_dog.m4a',
            'playful_growl.mp3',
            'alert_bark.wav',
            'calm_breathing.m4a',
            'excited_jumping.mp3',
            'anxious_whine.wav',
            'curious_sniff.m4a',
        ];

        $users = User::all();

        for ($i = 0; $i < $count; $i++) {
            $user = $users->random();
            $daysAgo = $this->getWeightedRandomDays();
            $isSuccessful = rand(0, 100) < 85;

            if ($isSuccessful) {
                $metadata = [
                    'prediction_result' => [
                        'breed' => $breeds[array_rand($breeds)],
                        'emotion' => $emotions[array_rand($emotions)],
                        'language' => $languages[array_rand($languages)],
                        'confidence' => rand(75, 98) / 100,
                        'processing_time' => rand(1, 5) . 's'
                    ],
                    'file_info' => [
                        'original_format' => ['mp3', 'wav', 'm4a'][array_rand(['mp3', 'wav', 'm4a'])],
                        'converted' => rand(0, 1) === 1,
                        'quality' => ['high', 'medium', 'low'][array_rand(['high', 'medium', 'low'])]
                    ],
                    'user_agent' => 'DogTranslation App v1.0',
                    'timestamp' => now()->subDays($daysAgo)->toISOString()
                ];
            } else {
                $metadata = [
                    'model_prediction' => 'failed',
                    'model_error' => 'Model service temporarily unavailable',
                    'file_info' => [
                        'original_format' => ['mp3', 'wav', 'm4a'][array_rand(['mp3', 'wav', 'm4a'])],
                        'converted' => rand(0, 1) === 1,
                        'quality' => ['high', 'medium', 'low'][array_rand(['high', 'medium', 'low'])]
                    ],
                    'user_agent' => 'DogTranslation App v1.0',
                    'timestamp' => now()->subDays($daysAgo)->toISOString()
                ];
            }

            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            AudioFile::create([
                'user_id' => $user->id,
                'filename' => 'sample_' . uniqid() . '.mp3',
                'original_filename' => $filenames[array_rand($filenames)],
                'file_path' => 'audio/' . $user->id . '/sample_' . uniqid() . '.mp3',
                'file_type' => 'audio/mpeg',
                'file_size' => rand(500000, 5000000),
                'duration' => rand(5, 60),
                'transcription' => $isSuccessful ? 'Detected: ' . ($metadata['prediction_result']['breed'] ?? 'Unknown') . ' - ' . ($metadata['prediction_result']['emotion'] ?? 'Unknown') : null,
                'metadata' => $metadata,
                'is_favorite' => rand(0, 10) < 3,
                'play_count' => rand(0, 15),
                'last_played_at' => rand(0, 10) < 6 ? $createdAt->copy()->addDays(rand(0, min($daysAgo, 7))) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addMinutes(rand(0, 60))
            ]);
        }
    }

    private function getWeightedRandomDays(): int
    {
        $rand = rand(0, 100);
        
        if ($rand < 60) {
            return rand(0, 6);
        } elseif ($rand < 90) {
            return rand(7, 13);
        } else {
            return rand(14, 30);
        }
    }
}



























