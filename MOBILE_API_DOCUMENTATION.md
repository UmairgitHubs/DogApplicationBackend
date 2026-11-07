# Mobile App API Documentation

This document describes the API endpoints for the mobile application.

## Base URL
```
http://your-domain.com/api
```

## Authentication
Most endpoints require authentication using Laravel Sanctum. Include the bearer token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN
```

---

## 1. Get Dog Breeds

**Endpoint:** `GET /api/breeds`

**Description:** Retrieves all available dog breeds from the database. This is a public endpoint (no authentication required).

**Request:**
```http
GET /api/breeds
```

**Response (Success - 200):**
```json
{
    "status": true,
    "message": "Dog breeds retrieved successfully",
    "data": {
        "breeds": [
            {
                "id": 1,
                "breed_name": "Golden Retriever",
                "image_url": "http://your-domain.com/images/dogs/GoldenRetriever.jpg",
                "image_path": "GoldenRetriever.jpg",
                "bark_characteristics": "High pitch, soft tone",
                "response_voice_style": "Calm, reassuring",
                "frequency": "Frequent",
                "analysis_count": 35,
                "created_at": "2026-01-25T13:00:00.000000Z",
                "updated_at": "2026-01-25T13:00:00.000000Z"
            },
            {
                "id": 2,
                "breed_name": "German Shepherd",
                "image_url": "http://your-domain.com/images/dogs/GermanShepherd.jpg",
                "image_path": "GermanShepherd.jpg",
                "bark_characteristics": "Low pitch, sharp tone",
                "response_voice_style": "Authoritative, clear",
                "frequency": "Moderate",
                "analysis_count": 28,
                "created_at": "2026-01-25T13:00:00.000000Z",
                "updated_at": "2026-01-25T13:00:00.000000Z"
            }
        ]
    }
}
```

**Response (Error - 500):**
```json
{
    "status": false,
    "message": "Failed to retrieve dog breeds",
    "error": "Error message"
}
```

---

## 2. Save Emotion Detection Data

**Endpoint:** `POST /api/emotion-detection`

**Description:** Saves AI emotion detection data from the mobile app to the database. This data will automatically appear in the admin dashboard.

**Authentication:** Required (Bearer Token)

**Request Body:**
```json
{
    "breed": "Golden Retriever",
    "emotion": "Happy",
    "confidence": 0.95,
    "language": "English",
    "transcription": "Optional transcription text"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| breed | string | Yes | Dog breed name (max 255 characters) |
| emotion | string | Yes | Detected emotion (max 255 characters) |
| confidence | float | Yes | Confidence score between 0 and 1 |
| language | string | No | Language of detection (default: "English") |
| transcription | string | No | Optional transcription text (max 5000 characters) |

**Note:** This endpoint does NOT accept audio files or file paths. It only accepts emotion detection parameters that will be displayed on the admin dashboard and saved in the database.

**Request Example (cURL):**
```bash
curl -X POST "http://your-domain.com/api/emotion-detection" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "breed": "Golden Retriever",
    "emotion": "Happy",
    "confidence": 0.95,
    "language": "English"
  }'
```

**Response (Success - 201):**
```json
{
    "status": true,
    "message": "Emotion detection data saved successfully",
    "data": {
        "detection_id": 123,
        "breed": "Golden Retriever",
        "emotion": "Happy",
        "confidence": 0.95,
        "created_at": "2026-01-25T14:30:00.000000Z"
    }
}
```

**Response (Validation Error - 422):**
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "breed": ["The breed field is required."],
        "confidence": ["The confidence must be between 0 and 1."]
    }
}
```

**Response (Error - 500):**
```json
{
    "status": false,
    "message": "Failed to save emotion detection data",
    "error": "Error message"
}
```

---

## Data Integration with Dashboard

The saved emotion detection data automatically appears in the admin dashboard:

1. **AI Emotions Monitoring Page** (`/admin/emotions`):
   - Shows detection counts (Today, This Week, This Month)
   - Displays emotion distribution chart
   - Lists recent detection logs with user, dog breed, emotion, and confidence

2. **Dog Breed Management Page** (`/admin/breeds`):
   - Updates breed analysis counts automatically
   - Shows breed statistics in the "Top Analyzed Breeds" chart

3. **Advanced Analytics Page** (`/admin/analytics`):
   - Includes emotion detection data in analytics
   - Updates breed distribution charts

---

## Notes

- The `breed` parameter will automatically create a new breed entry if it doesn't exist in the database
- The `analysis_count` for the breed is automatically incremented when detection is saved
- All saved detections are linked to the authenticated user
- The data structure matches the existing audio file metadata format, ensuring seamless integration with the dashboard

---

## Error Handling

All endpoints return consistent error responses:
- **422**: Validation errors (missing or invalid parameters)
- **401**: Authentication required (missing or invalid token)
- **500**: Server errors (database issues, etc.)

Always check the `status` field in the response to determine success or failure.



