<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AudioFile;
use Carbon\Carbon;

class GenerateSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:generate-sample-data {--count=50 : Number of sample records to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sample data for dashboard testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');

        $this->info("Generating comprehensive sample data...");
        $this->info("Creating users and {$count} sample audio files...");

        // Create users first if none exist
        $existingUsers = User::count();
        $usersToCreate = max(0, 20 - $existingUsers);
        
        if ($usersToCreate > 0) {
            $this->info("Creating {$usersToCreate} sample users...");
            for ($i = 0; $i < $usersToCreate; $i++) {
                User::create([
                    'username' => 'user_' . ($existingUsers + $i + 1),
                    'name' => $this->getRandomName(),
                    'email' => 'user' . ($existingUsers + $i + 1) . '@example.com',
                    'password' => bcrypt('password'),
                    'email_verified' => rand(0, 10) > 1, // 90% verified
                ]);
            }
        }

        // Sample breeds with weighted distribution (more common breeds appear more)
        $breeds = [
            'Golden Retriever', 'Golden Retriever', 'Golden Retriever', // More common
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
            'Dachshund',
            'Shih Tzu',
            'Boxer',
            'Great Dane'
        ];

        // Sample emotions with weighted distribution
        $emotions = [
            'Happy', 'Happy', 'Happy', 'Happy', // More common
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

        // Sample languages with weighted distribution
        $languages = [
            'English', 'English', 'English', 'English', 'English', 'English', // Most common
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

        // Sample filenames
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
            'dog_howling.mp3',
            'puppy_crying.wav',
            'happy_panting.m4a',
            'aggressive_growl.mp3',
            'playful_bark.wav',
            'sleepy_snoring.m4a'
        ];

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $users = User::all();
        if ($users->isEmpty()) {
            $this->error("No users found. Please create users first.");
            return 1;
        }

        for ($i = 0; $i < $count; $i++) {
            // Get a random user
            $user = $users->random();

            // Create more recent activity (60% in last 7 days, 30% in last 14 days, 10% older)
            $daysAgo = $this->getWeightedRandomDays();

            // 85% success rate, 15% failed predictions
            $isSuccessful = rand(0, 100) < 85;

            if ($isSuccessful) {
                // Generate successful prediction metadata
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
                // Generate failed prediction metadata
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

            // Create audio file with realistic dates
            $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            AudioFile::create([
                'user_id' => $user->id,
                'filename' => 'sample_' . uniqid() . '.mp3',
                'original_filename' => $filenames[array_rand($filenames)],
                'file_path' => 'audio/' . $user->id . '/sample_' . uniqid() . '.mp3',
                'file_type' => 'audio/mpeg',
                'file_size' => rand(500000, 5000000), // 500KB to 5MB
                'duration' => rand(5, 60), // 5 to 60 seconds
                'transcription' => $isSuccessful ? 'Detected: ' . ($metadata['prediction_result']['breed'] ?? 'Unknown') . ' - ' . ($metadata['prediction_result']['emotion'] ?? 'Unknown') : null,
                'metadata' => $metadata,
                'is_favorite' => rand(0, 10) < 3, // 30% favorites
                'play_count' => rand(0, 15),
                'last_played_at' => rand(0, 10) < 6 ? $createdAt->copy()->addDays(rand(0, min($daysAgo, 7))) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addMinutes(rand(0, 60))
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->newLine();

        $totalUsers = User::count();
        $totalFiles = AudioFile::count();
        $verifiedUsers = User::where('email_verified', true)->count();

        $this->info("✅ Sample data generation completed!");
        $this->info("📊 Statistics:");
        $this->info("   - Total Users: {$totalUsers}");
        $this->info("   - Verified Users: {$verifiedUsers}");
        $this->info("   - Total Audio Files: {$totalFiles}");
        $this->info("🔗 Visit /admin/dashboard to see the results");
    }

    /**
     * Get weighted random days (more recent activity)
     */
    private function getWeightedRandomDays(): int
    {
        $rand = rand(0, 100);
        
        if ($rand < 60) {
            // 60% in last 7 days
            return rand(0, 6);
        } elseif ($rand < 90) {
            // 30% in last 14 days
            return rand(7, 13);
        } else {
            // 10% older
            return rand(14, 30);
        }
    }

    /**
     * Get random name
     */
    private function getRandomName(): string
    {
        $firstNames = ['John', 'Jane', 'Mike', 'Sarah', 'David', 'Emily', 'Chris', 'Lisa', 'Tom', 'Amy', 'James', 'Emma', 'Robert', 'Olivia', 'Michael', 'Sophia'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Wilson', 'Anderson', 'Thomas', 'Taylor'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}
