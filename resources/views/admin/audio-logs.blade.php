<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Logs - DogTranslation.com Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #58A509;
            --dark-bg: #1f2937;
            --sidebar-bg: #111827;
            --card-border: #e5e7eb;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f9fafb;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .content-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .audio-player {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 0.5rem 0;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Include the sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-microphone-alt text-primary me-2"></i>
                Audio Logs
            </h1>
            <p class="page-subtitle">Monitor and manage all audio file uploads and processing</p>
        </div>

        <div class="content-card">
            <div class="row mb-4">
                <div class="col-md-4">
                    <form method="GET" class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search audio files..." name="search" value="{{ request('search') }}">
                        </div>
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" class="d-flex">
                        <select class="form-select" name="user_id" onchange="this.form.submit()">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" class="d-flex">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    </form>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" onclick="exportAudioLogs()">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>File</th>
                            <th>User</th>
                            <th>Duration</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Uploaded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="audioTableBody">
                        @if($audioFiles->count() > 0)
                        @foreach($audioFiles as $file)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($file->is_emotion_detection ?? false)
                                    <i class="fas fa-heart text-danger me-2" title="Emotion Detection"></i>
                                    @else
                                    <i class="fas fa-file-audio text-primary me-2"></i>
                                    @endif
                                    <div>
                                        <div class="fw-medium">
                                            {{ $file->original_filename }}
                                            @if($file->is_emotion_detection ?? false)
                                            <span class="badge bg-info ms-2" style="font-size: 0.7rem;">Emotion Detection</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            @if($file->metadata && isset($file->metadata['prediction_result']))
                                                {{ $file->metadata['prediction_result']['emotion'] ?? 'N/A' }} - {{ $file->metadata['prediction_result']['breed'] ?? 'N/A' }}
                                            @else
                                                {{ $file->file_type }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ $file->user ? strtoupper(substr($file->user->name, 0, 1)) : '?' }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $file->user ? $file->user->name : 'Unknown User' }}</div>
                                        <small class="text-muted">{{ $file->user ? $file->user->email : 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $file->formatted_duration }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $file->formatted_file_size }}</span>
                            </td>
                            <td>
                                @php
                                $statusClass = 'bg-warning';
                                $statusText = 'Pending';

                                if ($file->metadata) {
                                if (isset($file->metadata['model_prediction']) && $file->metadata['model_prediction'] === 'failed') {
                                $statusClass = 'bg-danger';
                                $statusText = 'Failed';
                                } elseif (isset($file->metadata['prediction_result'])) {
                                $statusClass = 'bg-success';
                                $statusText = 'Processed';
                                }
                                }
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($file->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($file->file_url && !($file->is_emotion_detection ?? false))
                                    <button class="btn btn-outline-primary" data-file-url="{{ $file->file_url }}" onclick="playAudio(this.dataset.fileUrl)" title="Play Audio">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-outline-secondary" disabled title="No audio file (emotion detection only)">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-outline-info" data-file-id="{{ $file->id }}" onclick="viewDetails(this.dataset.fileId)" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" data-file-id="{{ $file->id }}" onclick="deleteAudio(this.dataset.fileId)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-microphone-alt text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted">No audio files found</p>
                                <small class="text-muted">Audio files will appear here when users upload them through your app</small>
                                <div class="mt-3">
                                    <a href="{{ route('admin.generate-sample-data') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-magic me-1"></i>
                                        Generate Sample Audio Files
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <nav aria-label="Audio files pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="audioPagination">
                    @if($audioFiles->lastPage() > 1)
                    <li class="page-item {{ $audioFiles->currentPage() === 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $audioFiles->previousPageUrl() }}">Previous</a>
                    </li>

                    @for($i = 1; $i <= $audioFiles->lastPage(); $i++)
                        <li class="page-item {{ $audioFiles->currentPage() === $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $audioFiles->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        <li class="page-item {{ $audioFiles->currentPage() === $audioFiles->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $audioFiles->nextPageUrl() }}">Next</a>
                        </li>
                        @endif
                </ul>

                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing {{ $audioFiles->firstItem() ?? 0 }} to {{ $audioFiles->lastItem() ?? 0 }} of {{ $audioFiles->total() }} audio files
                    </small>
                </div>
            </nav>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Audio action functions
        function playAudio(fileUrl) {
            // Create audio player modal or inline player
            const audio = new Audio(fileUrl);
            audio.play().catch(e => {
                alert('Unable to play audio file: ' + e.message);
            });
        }

        function viewDetails(fileId) {
            alert('View details for file: ' + fileId + '\n\nThis feature will be implemented in a future update.');
        }

        function deleteAudio(fileId) {
            if (confirm('Are you sure you want to delete this audio file?')) {
                alert('Delete audio file: ' + fileId + '\n\nThis feature will be implemented in a future update.');
            }
        }

        function exportAudioLogs() {
            alert('Export audio logs functionality will be implemented in a future update.');
        }
    </script>
</body>

</html>