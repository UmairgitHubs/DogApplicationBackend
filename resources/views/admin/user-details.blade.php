<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - DogTranslation.com Admin</title>
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

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <main class="main-content">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Users
            </a>
        </div>

        <!-- Error/Success Messages -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="page-title mb-0">{{ $user->name }}</h1>
                    <p class="page-subtitle mb-0">@{{ $user->username }} • {{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="content-card text-center">
                    <i class="fas fa-file-audio text-primary mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ $totalAudioFiles }}</h3>
                    <p class="text-muted mb-0">Total Audio Files</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <i class="fas fa-play text-success mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ $totalPlayCount }}</h3>
                    <p class="text-muted mb-0">Total Plays</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <i class="fas fa-heart text-danger mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ $favoriteCount }}</h3>
                    <p class="text-muted mb-0">Favorites</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <i class="fas fa-clock text-warning mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ $lastActivity ? \Carbon\Carbon::parse($lastActivity)->diffForHumans() : 'Never' }}</h3>
                    <p class="text-muted mb-0">Last Activity</p>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="fas fa-user me-2"></i>
                        User Information
                    </h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-medium">Name:</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Username:</td>
                            <td>@{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Email:</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Status:</td>
                            <td>
                                <span class="badge {{ $user->email_verified ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->email_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Member Since:</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Last Updated:</td>
                            <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-line me-2"></i>
                        Activity Summary
                    </h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-primary mb-1">{{ $totalAudioFiles }}</div>
                                <small class="text-muted">Audio Files</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-success mb-1">{{ $totalPlayCount }}</div>
                                <small class="text-muted">Total Plays</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-danger mb-1">{{ $favoriteCount }}</div>
                                <small class="text-muted">Favorites</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 text-warning mb-1">{{ $lastActivity ? \Carbon\Carbon::parse($lastActivity)->diffForHumans() : 'Never' }}</div>
                                <small class="text-muted">Last Active</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Audio Files -->
        @if($recentAudioFiles->count() > 0)
        <div class="content-card mt-4">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>
                Recent Audio Files
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Duration</th>
                            <th>Plays</th>
                            <th>Favorite</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAudioFiles as $audioFile)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-audio text-primary me-2"></i>
                                    <div>
                                        <div class="fw-medium">{{ $audioFile->original_filename }}</div>
                                        <small class="text-muted">{{ $audioFile->transcription ? Str::limit($audioFile->transcription, 50) : 'No transcription' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $audioFile->duration }}s</td>
                            <td>
                                <span class="badge bg-info">{{ $audioFile->play_count }}</span>
                            </td>
                            <td>
                                @if($audioFile->is_favorite)
                                <i class="fas fa-heart text-danger"></i>
                                @else
                                <i class="fas fa-heart text-muted"></i>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($audioFile->created_at)->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="content-card mt-4">
            <div class="text-center py-4">
                <i class="fas fa-file-audio text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No Audio Files</h5>
                <p class="text-muted">This user hasn't uploaded any audio files yet.</p>
            </div>
        </div>
        @endif
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>