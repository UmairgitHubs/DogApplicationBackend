# Bluehost Deployment Guide

## Quick Fix for Current Error

The error you're seeing is because the authentication middleware is trying to redirect to a `login` route that doesn't exist. This has been fixed in the `app/Http/Middleware/Authenticate.php` file.

## Deployment Steps

### 1. Upload Your Code

Upload these files/folders to your Bluehost public_html directory:

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/ (contents go to public_html root)
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
├── composer.lock
└── .env
```

### 2. Set Up Environment File

Create `.env` file in your public_html directory:

```env
APP_NAME="Dog Emotion Detection"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# For audio conversion (will show error if FFmpeg not available)
# This is expected on shared hosting
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Database Migrations

```bash
php artisan migrate --force
```

### 5. Set File Permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Test Your API

Visit: `https://yourdomain.com/api/health`

You should see:

```json
{
    "status": "ok",
    "message": "API is running",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "version": "1.0.0"
}
```

## Expected Behavior on Bluehost Shared Hosting

### ✅ What Will Work:

-   API endpoints
-   Authentication
-   Database operations
-   File uploads
-   Model predictions (if model URL is accessible)

### ❌ What Won't Work:

-   Audio conversion (FFmpeg not available)
-   You'll get this error: "Audio conversion service is not available. FFmpeg is required."

### 🔧 Solutions for Audio Conversion:

#### Option 1: Accept MP3 Files Only

Modify your frontend to only accept MP3 files, or add validation to reject non-MP3 files with a helpful message.

#### Option 2: Upgrade to VPS Hosting

-   Bluehost VPS: ~$20-40/month
-   DigitalOcean: ~$5-10/month
-   AWS EC2: ~$10-20/month

#### Option 3: Use External Conversion Service

Implement a service that uses external APIs for audio conversion.

## Testing Your Deployment

### 1. Test Health Endpoint

```bash
curl https://yourdomain.com/api/health
```

### 2. Test Authentication

```bash
curl -X POST https://yourdomain.com/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123"}'
```

### 3. Test Audio Upload (will fail conversion but should work for MP3)

```bash
curl -X POST https://yourdomain.com/api/audio/convert-and-predict \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "file=@test.mp3"
```

## Common Issues and Solutions

### Issue: "Route [login] not defined"

**Solution**: Fixed in the updated `Authenticate.php` middleware.

### Issue: "FFmpeg not found"

**Expected**: This is normal on shared hosting. Your app will work but won't convert audio files.

### Issue: "Permission denied"

**Solution**:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Issue: "Database connection failed"

**Solution**: Check your database credentials in `.env` file.

## Production Optimizations

### 1. Clear Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Optimize Autoloader

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Set Proper Permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## Monitoring

Check your logs at: `storage/logs/laravel.log`

## Support

If you need full audio conversion functionality, consider:

1. Upgrading to VPS hosting
2. Using external conversion services
3. Pre-converting files on the client side

Your API will work perfectly for MP3 files and model predictions!
