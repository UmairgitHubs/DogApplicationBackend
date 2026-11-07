<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Module - DogTranslation.com Admin</title>
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

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 2rem;
        }

        .timeline-marker {
            position: absolute;
            left: -1.5rem;
            top: 0.5rem;
            width: 2rem;
            height: 2rem;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .timeline-content {
            background: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            border-left: 3px solid #e5e7eb;
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
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-redo text-primary me-2"></i>
                History Module
            </h1>
            <p class="page-subtitle">View and manage user activity history and audio file interactions</p>
        </div>

        <!-- Activity Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="content-card text-center">
                    <i class="fas fa-upload text-primary mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ number_format($stats['total_uploads']) }}</h3>
                    <p class="text-muted mb-0">Total Uploads</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="content-card text-center">
                    <i class="fas fa-cogs text-success mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ number_format($stats['total_processed']) }}</h3>
                    <p class="text-muted mb-0">Processed</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="content-card text-center">
                    <i class="fas fa-play text-info mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ number_format($stats['total_plays']) }}</h3>
                    <p class="text-muted mb-0">Total Plays</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="content-card text-center">
                    <i class="fas fa-heart text-danger mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ number_format($stats['total_favorites']) }}</h3>
                    <p class="text-muted mb-0">Favorites</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="content-card text-center">
                    <i class="fas fa-users text-warning mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-1">{{ number_format($stats['unique_users']) }}</h3>
                    <p class="text-muted mb-0">Active Users</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="content-card mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <form method="GET" class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search activities..." name="search" value="{{ request('search') }}">
                        </div>
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="activity_type" value="{{ request('activity_type') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    </form>
                </div>
                <div class="col-md-2">
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
                        <input type="hidden" name="activity_type" value="{{ request('activity_type') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" class="d-flex">
                        <select class="form-select" name="activity_type" onchange="this.form.submit()">
                            <option value="">All Activities</option>
                            <option value="upload" {{ request('activity_type') == 'upload' ? 'selected' : '' }}>Uploads</option>
                            <option value="processed" {{ request('activity_type') == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="failed" {{ request('activity_type') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="played" {{ request('activity_type') == 'played' ? 'selected' : '' }}>Played</option>
                            <option value="favorited" {{ request('activity_type') == 'favorited' ? 'selected' : '' }}>Favorited</option>
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" class="d-flex">
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From Date">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="activity_type" value="{{ request('activity_type') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" class="d-flex">
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="To Date">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="activity_type" value="{{ request('activity_type') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    </form>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('admin.history') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="content-card">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>
                Activity Timeline
            </h5>

            @if($activities->count() > 0)
            <div class="timeline">
                @foreach($activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-marker">
                        @if($activity->activity_type === 'upload')
                        <i class="fas fa-upload text-primary"></i>
                        @elseif($activity->activity_type === 'processed')
                        <i class="fas fa-cogs text-success"></i>
                        @elseif($activity->activity_type === 'failed')
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        @elseif($activity->activity_type === 'played')
                        <i class="fas fa-play text-info"></i>
                        @elseif($activity->activity_type === 'favorited')
                        <i class="fas fa-heart text-danger"></i>
                        @else
                        <i class="fas fa-circle text-secondary"></i>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $activity->activity_description }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $activity->user ? $activity->user->name : 'Unknown User' }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </p>
                                @if($activity->transcription)
                                <small class="text-muted">
                                    <i class="fas fa-quote-left me-1"></i>
                                    {{ Str::limit($activity->transcription, 100) }}
                                </small>
                                @endif
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y H:i') }}
                                </span>
                                @if($activity->duration)
                                <br><small class="text-muted">{{ $activity->duration }}s</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <nav aria-label="Activity pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    @if($activities->lastPage() > 1)
                    <li class="page-item {{ $activities->currentPage() === 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $activities->previousPageUrl() }}">Previous</a>
                    </li>

                    @for($i = 1; $i <= $activities->lastPage(); $i++)
                        <li class="page-item {{ $activities->currentPage() === $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $activities->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        <li class="page-item {{ $activities->currentPage() === $activities->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $activities->nextPageUrl() }}">Next</a>
                        </li>
                        @endif
                </ul>

                <div class="text-center mt-2">
                    <small class="text-muted">
                        Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} activities
                    </small>
                </div>
            </nav>
            @else
            <div class="text-center py-5">
                <i class="fas fa-history text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No Activity Found</h5>
                <p class="text-muted">No activities match your current filters.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.generate-sample-data') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-magic me-1"></i>
                        Generate Sample Activity
                    </a>
                </div>
            </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>