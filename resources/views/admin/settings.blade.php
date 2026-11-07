<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Settings - DogTranslation.com Admin</title>
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
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cog text-primary me-2"></i>
                App Settings
            </h1>
            <p class="page-subtitle">Configure application settings and preferences</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="content-card">
                    <h5 class="mb-4">General Settings</h5>
                    <form id="settingsForm">
                        <div class="mb-3">
                            <label class="form-label">Application Name</label>
                            <input type="text" class="form-control" id="appName" value="DogTranslation.com" readonly>
                            <small class="text-muted">Application name (configured in .env)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Application URL</label>
                            <input type="text" class="form-control" id="appUrl" value="{{ config('app.url') }}" readonly>
                            <small class="text-muted">Base URL for the application</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Environment</label>
                            <input type="text" class="form-control" id="appEnv" value="{{ config('app.env') }}" readonly>
                            <small class="text-muted">Current environment ({{ config('app.env') }})</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Debug Mode</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="debugMode" {{ config('app.debug') ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="debugMode">
                                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                </label>
                            </div>
                            <small class="text-muted">Debug mode status (configured in .env)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maintenance Mode</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                <label class="form-check-label" for="maintenanceMode">
                                    Enable maintenance mode
                                </label>
                            </div>
                            <small class="text-muted">Put the application in maintenance mode</small>
                        </div>
                    </form>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-4">System Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>PHP Version:</strong>
                            <div class="text-muted">{{ PHP_VERSION }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Laravel Version:</strong>
                            <div class="text-muted">{{ app()->version() }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Database Connection:</strong>
                            <div class="text-muted">{{ config('database.default') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Cache Driver:</strong>
                            <div class="text-muted">{{ config('cache.default') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Session Driver:</strong>
                            <div class="text-muted">{{ config('session.driver') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Queue Driver:</strong>
                            <div class="text-muted">{{ config('queue.default') }}</div>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-4">Storage Information</h5>
                    <div id="storageInfo">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="content-card">
                    <h5 class="mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i> Clear Cache
                        </button>
                        <button class="btn btn-warning" onclick="clearLogs()">
                            <i class="fas fa-trash me-2"></i> Clear Logs
                        </button>
                        <button class="btn btn-info" onclick="optimizeDatabase()">
                            <i class="fas fa-database me-2"></i> Optimize Database
                        </button>
                        <button class="btn btn-success" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-2"></i> Refresh Data
                        </button>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-3">System Health</h5>
                    <div id="systemHealth">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Database</span>
                                <span class="badge bg-success" id="dbStatus">Connected</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Storage</span>
                                <span class="badge bg-success" id="storageStatus">Available</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cache</span>
                                <span class="badge bg-success" id="cacheStatus">Active</span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>API</span>
                                <span class="badge bg-success" id="apiStatus">Online</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-3">Recent Activity</h5>
                    <div id="recentActivity">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshData() {
            loadStorageInfo();
            checkSystemHealth();
            loadRecentActivity();
        }

        function loadStorageInfo() {
            fetch('/api/admin/dashboard/stats', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const storageDiv = document.getElementById('storageInfo');
                        const totalDetections = data.data.kpi.totalDetections || 0;
                        const totalUsers = data.data.kpi.totalUsers || 0;
                        
                        storageDiv.innerHTML = `
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <strong>Total Audio Files:</strong>
                                    <div class="text-muted">${totalDetections.toLocaleString()}</div>
                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Total Users:</strong>
                                    <div class="text-muted">${totalUsers.toLocaleString()}</div>
                                </div>
                                <div class="col-12">
                                    <strong>Storage Path:</strong>
                                    <div class="text-muted">storage/app/public</div>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading storage info:', error);
                });
        }

        function checkSystemHealth() {
            // Check database
            fetch('/api/health', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        document.getElementById('apiStatus').textContent = 'Online';
                        document.getElementById('apiStatus').className = 'badge bg-success';
                    } else {
                        document.getElementById('apiStatus').textContent = 'Offline';
                        document.getElementById('apiStatus').className = 'badge bg-danger';
                    }
                })
                .catch(error => {
                    document.getElementById('apiStatus').textContent = 'Error';
                    document.getElementById('apiStatus').className = 'badge bg-danger';
                });
        }

        function loadRecentActivity() {
            fetch('/api/admin/audio-files?per_page=5', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status && data.data.audio_files.length > 0) {
                        const activityDiv = document.getElementById('recentActivity');
                        activityDiv.innerHTML = data.data.audio_files.map(file => {
                            const date = new Date(file.created_at).toLocaleString();
                            return `<div class="mb-2">
                                <small class="d-block">${file.original_filename || 'Unknown'}</small>
                                <small class="text-muted">${date}</small>
                            </div>`;
                        }).join('');
                    } else {
                        document.getElementById('recentActivity').innerHTML = '<p class="text-muted text-center">No recent activity</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading recent activity:', error);
                });
        }

        function clearCache() {
            if (confirm('Are you sure you want to clear the cache?')) {
                alert('Cache cleared! (This is a demo action)');
            }
        }

        function clearLogs() {
            if (confirm('Are you sure you want to clear the logs?')) {
                alert('Logs cleared! (This is a demo action)');
            }
        }

        function optimizeDatabase() {
            if (confirm('Are you sure you want to optimize the database?')) {
                alert('Database optimized! (This is a demo action)');
            }
        }

        // Load initial data
        refreshData();
    </script>
</body>

</html>