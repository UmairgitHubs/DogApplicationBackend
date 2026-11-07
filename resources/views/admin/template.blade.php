<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Admin Page' }} - DogTranslation.com Admin</title>
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

        .feature-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
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
                <i class="{{ $pageIcon ?? 'fas fa-cog' }} text-primary me-2"></i>
                {{ $pageTitle ?? 'Admin Page' }}
            </h1>
            <p class="page-subtitle">{{ $pageSubtitle ?? 'Manage and configure this section' }}</p>
        </div>

        <div class="content-card">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3">Coming Soon</h5>
                    <p class="text-muted">
                        This section is currently under development. We're working hard to bring you
                        comprehensive management tools for {{ strtolower($pageTitle ?? 'this feature') }}.
                    </p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What's coming:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Advanced filtering and search capabilities</li>
                            <li>Real-time data updates</li>
                            <li>Export and reporting features</li>
                            <li>Bulk operations and management tools</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-chart-line text-primary mb-3" style="font-size: 2rem;"></i>
                        <h6>Analytics</h6>
                        <p class="text-muted small">Track performance and usage metrics</p>
                    </div>

                    <div class="feature-card mt-3">
                        <i class="fas fa-cogs text-success mb-3" style="font-size: 2rem;"></i>
                        <h6>Configuration</h6>
                        <p class="text-muted small">Customize settings and preferences</p>
                    </div>

                    <div class="feature-card mt-3">
                        <i class="fas fa-download text-info mb-3" style="font-size: 2rem;"></i>
                        <h6>Export Data</h6>
                        <p class="text-muted small">Download reports and data</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>