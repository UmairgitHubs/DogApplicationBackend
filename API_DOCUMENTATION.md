# DogTranslation.com API Documentation

## Base URL

```
http://localhost:8000/api
```

## Authentication

This API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected routes.

## Public Endpoints

### 1. User Registration

**POST** `/register`

Register a new user account. An OTP will be sent to the provided email for verification.

**Request Body:**

```json
{
    "username": "johndoe",
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**

```json
{
    "status": true,
    "message": "Registration successful. Please check your email for verification code.",
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified": false,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "requires_verification": true
    }
}
```

### 2. Email Verification (OTP)

**POST** `/verify-otp`

Verify the OTP sent to user's email and complete registration.

**Request Body:**

```json
{
    "user_id": 1,
    "otp": "1234"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Email verified successfully",
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified": true,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 3. Resend OTP

**POST** `/resend-otp`

Resend OTP to user's email if the previous one expired.

**Request Body:**

```json
{
    "user_id": 1
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "OTP sent successfully. Please check your email.",
    "data": {
        "user_id": 1
    }
}
```

### 4. User Login

**POST** `/login`

Login with email and password. Email must be verified.

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified": true,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

**Response if email not verified (403):**

```json
{
    "status": false,
    "message": "Please verify your email before logging in",
    "data": {
        "requires_verification": true,
        "user_id": 1
    }
}
```

### 5. Forgot Password

**POST** `/forgot-password`

Request a password reset OTP to be sent to the user's email.

**Request Body:**

```json
{
    "email": "john@example.com"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Password reset OTP sent successfully. Please check your email.",
    "data": {
        "user_id": 1,
        "email": "john@example.com",
        "otp_expires_in_minutes": 15
    }
}
```

**Response if email not found (422):**

```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "email": ["No account found with this email address"]
    }
}
```

### 6. Reset Password

**POST** `/reset-password`

Reset user password using the OTP received via email.

**Request Body:**

```json
{
    "user_id": 1,
    "otp": "1234",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Password reset successfully. Please login with your new password.",
    "data": {
        "user_id": 1,
        "email": "john@example.com"
    }
}
```

**Response if OTP is invalid/expired (400):**

```json
{
    "status": false,
    "message": "Invalid or expired OTP"
}
```

## Protected Endpoints

### 7. User Profile

**GET** `/profile`

Get authenticated user's profile information.

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "status": true,
    "message": "User profile retrieved successfully",
    "data": {
        "user": {
            "id": 1,
            "username": "johndoe",
            "name": "John Doe",
            "email": "john@example.com",
            "email_verified_at": "2024-01-01T00:00:00.000000Z",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### 8. User Logout

**POST** `/logout`

Logout and invalidate the current token.

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Logged out successfully"
}
```

### 9. Update User Profile

**PUT** `/profile`

Update authenticated user's profile information. All fields are optional.

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "username": "newusername",
    "name": "New Full Name",
    "email": "newemail@example.com",
    "current_password": "currentpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Profile updated successfully",
    "data": {
        "user": {
            "id": 1,
            "username": "newusername",
            "name": "New Full Name",
            "email": "newemail@example.com",
            "email_verified": false,
            "email_verified_at": null,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T12:00:00.000000Z"
        },
        "email_verification_required": true
    }
}
```

**Response if current password is incorrect (400):**

```json
{
    "status": false,
    "message": "Current password is incorrect"
}
```

**Response if email verification fails (500):**

```json
{
    "status": false,
    "message": "Profile updated but failed to send email verification. Please request a new verification email."
}
```

## Audio File Management Endpoints

### 10. Get Audio History

**GET** `/audio/history`

Get paginated list of all audio files for the authenticated user with search and sorting options.

**Headers:**

```
Authorization: Bearer {token}
```

**Query Parameters:**

-   `per_page` (optional): Number of items per page (default: 15)
-   `search` (optional): Search in filename or transcription
-   `sort_by` (optional): Sort field (created_at, last_played_at, play_count, duration, file_size)
-   `sort_order` (optional): Sort order (asc, desc)

**Response (200):**

```json
{
    "status": true,
    "message": "Audio history retrieved successfully",
    "data": {
        "audio_files": [
            {
                "id": 1,
                "filename": "uuid-filename.mp3",
                "original_filename": "my-audio.mp3",
                "file_url": "http://localhost:8000/storage/audio/1/uuid-filename.mp3",
                "file_type": "audio/mpeg",
                "file_size": 1024000,
                "formatted_file_size": "1.00 MB",
                "duration": 120,
                "formatted_duration": "02:00",
                "transcription": "This is the transcribed text",
                "metadata": { "bitrate": 128, "sample_rate": 44100 },
                "is_favorite": false,
                "play_count": 5,
                "last_played_at": "2024-01-01T12:00:00.000000Z",
                "created_at": "2024-01-01T10:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 15,
            "total": 75,
            "from": 1,
            "to": 15
        }
    }
}
```

### 11. Add Audio to History

**POST** `/audio/history`

Upload and add an audio file to the user's history.

**Headers:**

```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (multipart/form-data):**

-   `file` (required): Audio file (mp3, wav, m4a, aac, ogg, max 100MB)
-   `transcription` (optional): Text transcription of the audio
-   `metadata` (optional): JSON object with additional metadata
-   `duration` (optional): Duration in seconds

**Response (201):**

```json
{
    "status": true,
    "message": "Audio file added to history successfully",
    "data": {
        "audio_file": {
            "id": 1,
            "filename": "uuid-filename.mp3",
            "original_filename": "my-audio.mp3",
            "file_url": "http://localhost:8000/storage/audio/1/uuid-filename.mp3",
            "file_type": "audio/mpeg",
            "file_size": 1024000,
            "formatted_file_size": "1.00 MB",
            "duration": 120,
            "formatted_duration": "02:00",
            "transcription": "This is the transcribed text",
            "metadata": { "bitrate": 128, "sample_rate": 44100 },
            "is_favorite": false,
            "play_count": 0,
            "created_at": "2024-01-01T10:00:00.000000Z"
        }
    }
}
```

### 12. Get Recent Audio Files

**GET** `/audio/recent`

Get recently played audio files for the authenticated user.

**Headers:**

```
Authorization: Bearer {token}
```

**Query Parameters:**

-   `per_page` (optional): Number of items per page (default: 10)
-   `days` (optional): Number of days to look back (default: 7)

**Response (200):**

```json
{
    "status": true,
    "message": "Recent audio files retrieved successfully",
    "data": {
        "audio_files": [
            {
                "id": 1,
                "filename": "uuid-filename.mp3",
                "original_filename": "my-audio.mp3",
                "file_url": "http://localhost:8000/storage/audio/1/uuid-filename.mp3",
                "file_type": "audio/mpeg",
                "file_size": 1024000,
                "formatted_file_size": "1.00 MB",
                "duration": 120,
                "formatted_duration": "02:00",
                "transcription": "This is the transcribed text",
                "metadata": { "bitrate": 128, "sample_rate": 44100 },
                "is_favorite": false,
                "play_count": 5,
                "last_played_at": "2024-01-01T12:00:00.000000Z",
                "created_at": "2024-01-01T10:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 2,
            "per_page": 10,
            "total": 15,
            "from": 1,
            "to": 10
        },
        "period_days": 7
    }
}
```

### 13. Update Recent Audio File

**POST** `/audio/recent`

Update audio file status (mark as played, favorite, etc.).

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "audio_file_id": 1,
    "action": "played"
}
```

**Actions:**

-   `played`: Mark as played (updates last_played_at and increments play_count)
-   `favorited`: Mark as favorite
-   `unfavorited`: Remove from favorites

**Response (200):**

```json
{
    "status": true,
    "message": "Audio file updated successfully",
    "data": {
        "audio_file": {
            "id": 1,
            "is_favorite": false,
            "last_played_at": "2024-01-01T12:00:00.000000Z",
            "play_count": 6
        }
    }
}
```

### 14. Get Audio File Details

**GET** `/audio/{id}`

Get detailed information about a specific audio file.

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Audio file retrieved successfully",
    "data": {
        "audio_file": {
            "id": 1,
            "filename": "uuid-filename.mp3",
            "original_filename": "my-audio.mp3",
            "file_url": "http://localhost:8000/storage/audio/1/uuid-filename.mp3",
            "file_type": "audio/mpeg",
            "file_size": 1024000,
            "formatted_file_size": "1.00 MB",
            "duration": 120,
            "formatted_duration": "02:00",
            "transcription": "This is the transcribed text",
            "metadata": { "bitrate": 128, "sample_rate": 44100 },
            "is_favorite": false,
            "play_count": 5,
            "last_played_at": "2024-01-01T12:00:00.000000Z",
            "created_at": "2024-01-01T10:00:00.000000Z",
            "updated_at": "2024-01-01T12:00:00.000000Z"
        }
    }
}
```

### 15. Delete Audio File

**DELETE** `/audio/{id}`

Delete an audio file and remove it from storage.

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Audio file deleted successfully"
}
```

### 16. Clear Audio History

**DELETE** `/audio/clear-history`

Delete all audio files for the authenticated user. This action cannot be undone.

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "status": true,
    "message": "Audio history cleared successfully",
    "data": {
        "deleted_count": 25,
        "message": "Successfully deleted 25 audio file(s)"
    }
}
```

**Response if no files to delete (200):**

```json
{
    "status": true,
    "message": "Audio history cleared successfully",
    "data": {
        "deleted_count": 0,
        "message": "Successfully deleted 0 audio file(s)"
    }
}
```

### 17. Get Favorite Audio Files

**GET** `/audio/favorites`

Get all favorite audio files for the authenticated user.

**Headers:**

```
Authorization: Bearer {token}
```

**Query Parameters:**

-   `per_page` (optional): Number of items per page (default: 15)

**Response (200):**

```json
{
    "status": true,
    "message": "Favorite audio files retrieved successfully",
    "data": {
        "audio_files": [
            {
                "id": 1,
                "filename": "uuid-filename.mp3",
                "original_filename": "my-audio.mp3",
                "file_url": "http://localhost:8000/storage/audio/1/uuid-filename.mp3",
                "file_type": "audio/mpeg",
                "file_size": 1024000,
                "formatted_file_size": "1.00 MB",
                "duration": 120,
                "formatted_duration": "02:00",
                "transcription": "This is the transcribed text",
                "metadata": { "bitrate": 128, "sample_rate": 44100 },
                "is_favorite": true,
                "play_count": 5,
                "last_played_at": "2024-01-01T12:00:00.000000Z",
                "created_at": "2024-01-01T10:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 2,
            "per_page": 15,
            "total": 25,
            "from": 1,
            "to": 15
        }
    }
}
```

## Error Responses

### Validation Error (422)

```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Authentication Error (401)

```json
{
    "status": false,
    "message": "Invalid credentials"
}
```

### Server Error (500)

```json
{
    "status": false,
    "message": "Registration failed",
    "error": "Error details"
}
```

## OTP System Features

1. **4-digit OTP**: Generated randomly and sent via email
2. **Email verification OTP**: 10-minute expiration for account verification
3. **Password reset OTP**: 15-minute expiration for password reset
4. **Email verification required**: Users must verify email before login
5. **Resend functionality**: Users can request new OTP if needed
6. **Automatic cleanup**: OTP is cleared after successful verification
7. **Security**: All existing tokens are revoked after password reset

## Frontend Integration Flow

1. **Registration**: User registers → OTP sent → User enters OTP → Account activated
2. **Login**: User logs in → Check email verification → If verified, login successful
3. **Resend OTP**: If OTP expires → User requests resend → New OTP sent
4. **Forgot Password**: User requests password reset → OTP sent → User enters OTP + new password → Password reset
5. **Password Reset**: User enters OTP + new password → Password updated → All sessions invalidated

## Audio File Management Features

1. **File Upload**: Support for multiple audio formats (mp3, wav, m4a, aac, ogg)
2. **File Storage**: Secure storage with user-specific directories
3. **Metadata Support**: Store additional audio metadata (bitrate, sample rate, etc.)
4. **Transcription**: Optional text transcription for each audio file
5. **Play Tracking**: Track play count and last played timestamp
6. **Favorites**: Mark audio files as favorites
7. **Search & Filter**: Search by filename or transcription content
8. **Sorting**: Sort by various fields (date, play count, duration, etc.)
9. **Pagination**: Efficient pagination for large audio collections
10. **Recent Files**: Get recently played files within configurable time period

## React Native Integration

### File Upload Example

```javascript
const uploadAudioFile = async (
    audioFile,
    transcription = null,
    metadata = null
) => {
    const formData = new FormData();
    formData.append("file", {
        uri: audioFile.uri,
        type: audioFile.type,
        name: audioFile.name,
    });

    if (transcription) {
        formData.append("transcription", transcription);
    }

    if (metadata) {
        formData.append("metadata", JSON.stringify(metadata));
    }

    try {
        const response = await fetch(`${API_BASE_URL}/audio/history`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${userToken}`,
                "Content-Type": "multipart/form-data",
            },
            body: formData,
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Upload error:", error);
        throw error;
    }
};
```

### Get Audio History Example

```javascript
const getAudioHistory = async (
    page = 1,
    search = "",
    sortBy = "created_at"
) => {
    try {
        const params = new URLSearchParams({
            page: page.toString(),
            per_page: "15",
            sort_by: sortBy,
            sort_order: "desc",
        });

        if (search) {
            params.append("search", search);
        }

        const response = await fetch(
            `${API_BASE_URL}/audio/history?${params}`,
            {
                headers: {
                    Authorization: `Bearer ${userToken}`,
                },
            }
        );

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Get history error:", error);
        throw error;
    }
};
```

### Mark as Played Example

```javascript
const markAsPlayed = async (audioFileId) => {
    try {
        const response = await fetch(`${API_BASE_URL}/audio/recent`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${userToken}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                audio_file_id: audioFileId,
                action: "played",
            }),
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Mark as played error:", error);
        throw error;
    }
};
```

### Toggle Favorite Example

```javascript
const toggleFavorite = async (audioFileId, isFavorite) => {
    try {
        const response = await fetch(`${API_BASE_URL}/audio/recent`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${userToken}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                audio_file_id: audioFileId,
                action: isFavorite ? "favorited" : "unfavorited",
            }),
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Toggle favorite error:", error);
        throw error;
    }
};
```

### Update Profile Example

```javascript
const updateProfile = async (profileData) => {
    try {
        const response = await fetch(`${API_BASE_URL}/profile`, {
            method: "PUT",
            headers: {
                Authorization: `Bearer ${userToken}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(profileData),
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Update profile error:", error);
        throw error;
    }
};

// Usage examples:
// Update username only
await updateProfile({ username: "newusername" });

// Update name and email
await updateProfile({
    name: "New Full Name",
    email: "newemail@example.com",
});

// Update password
await updateProfile({
    current_password: "oldpassword123",
    password: "newpassword123",
    password_confirmation: "newpassword123",
});
```

### Clear Audio History Example

```javascript
const clearAudioHistory = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/audio/clear-history`, {
            method: "DELETE",
            headers: {
                Authorization: `Bearer ${userToken}`,
            },
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Clear history error:", error);
        throw error;
    }
};
```

## Email Configuration

Make sure to configure your email settings in `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dogtranslation.com
MAIL_FROM_NAME="DogTranslation.com"
```
