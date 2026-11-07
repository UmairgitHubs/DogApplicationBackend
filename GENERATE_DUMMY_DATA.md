# Generate Dummy Data for Dashboard

This guide explains how to generate sample data to see the dashboard working with realistic information.

## Method 1: Using Artisan Command (Recommended)

Run the following command to generate sample data:

```bash
php artisan dashboard:generate-sample-data --count=100
```

**Options:**
- `--count`: Number of sample audio files to generate (default: 50)

**Example:**
```bash
# Generate 100 audio files
php artisan dashboard:generate-sample-data --count=100

# Generate 200 audio files
php artisan dashboard:generate-sample-data --count=200
```

## Method 2: Using Database Seeder

1. Open `database/seeders/DatabaseSeeder.php`
2. Uncomment the seeder call:
```php
public function run(): void
{
    $this->call([
        DashboardDataSeeder::class,
    ]);
}
```

3. Run the seeder:
```bash
php artisan db:seed
```

## What Gets Generated

The dummy data includes:

### Users
- 20 sample users with realistic names
- 90% email verified (premium users)
- Random creation dates over the last 60 days

### Audio Files
- Realistic distribution of breeds (Golden Retriever, German Shepherd, etc.)
- Various emotions (Happy, Excited, Calm, Playful, etc.)
- Multiple languages (English, Spanish, French, etc.)
- 85% successful predictions, 15% failed
- Weighted date distribution:
  - 60% in last 7 days (recent activity)
  - 30% in last 14 days
  - 10% older (14-30 days)
- Realistic file sizes (500KB - 5MB)
- Random durations (5-60 seconds)
- Some favorites and play counts

## View the Dashboard

After generating data, visit:
- **Dashboard**: `/admin/dashboard`
- **User Management**: `/admin/users`
- **Breeds**: `/admin/breeds`
- **Emotions**: `/admin/emotions`
- **Analytics**: `/admin/analytics`
- **Payments**: `/admin/payments`
- **Multilingual**: `/admin/multilingual`

## Quick Start

```bash
# Generate 100 sample audio files
php artisan dashboard:generate-sample-data --count=100

# Visit the dashboard
# http://your-domain.com/admin/dashboard
```

## Notes

- The command will create users if none exist
- Existing users will be used if available
- Data is generated with realistic distributions for better dashboard visualization
- You can run the command multiple times to add more data



























