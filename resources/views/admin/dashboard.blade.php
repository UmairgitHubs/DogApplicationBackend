<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogTranslation.com - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Sidebar Styles */
        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            padding: 1.5rem;
            border-bottom: 1px solid #374151;
            flex-shrink: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #374151 var(--sidebar-bg);
            max-height: calc(100vh - 140px);
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            color: #d1d5db;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: white;
            background-color: #374151;
        }

        .nav-link.active {
            background-color: var(--primary-green);
            color: white;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #374151;
            flex-shrink: 0;
            margin-bottom: 2rem;
        }

        .sidebar-footer .nav-link {
            color: #d1d5db;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sidebar-footer .nav-link:hover {
            color: white !important;
            background-color: #dc2626 !important;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* KPI Cards */
        .kpi-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .kpi-title {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        /* Chart Cards */
        .chart-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .chart-metric {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .chart-subtitle {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .chart-subtitle.positive {
            color: var(--primary-green);
        }

        .chart-subtitle.negative {
            color: #ef4444;
        }

        /* KPI Card borders */
        .kpi-card {
            border-left: 4px solid var(--primary-green) !important;
        }

        /* Empty State Styling */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #9ca3af;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .empty-state-subtext {
            font-size: 12px;
            opacity: 0.7;
        }

        .kpi-empty {
            color: #9ca3af;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .metric-empty {
            color: #9ca3af;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Enhanced KPI Empty States */
        .kpi-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 80px;
            color: #9ca3af;
            text-align: center;
        }

        .kpi-empty-icon {
            font-size: 24px;
            margin-bottom: 8px;
            opacity: 0.6;
        }

        .kpi-empty-text {
            font-size: 12px;
            font-weight: 500;
            opacity: 0.8;
        }

        .kpi-value-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .kpi-value {
                font-size: 1.5rem;
            }
        }

        /* Toggle Button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-green);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            DogTranslation.com
        </div>

        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard Overview
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    User Management
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.user-details') }}" class="nav-link {{ request()->routeIs('admin.user-details') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i>
                    User Details
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.breeds') }}" class="nav-link {{ request()->routeIs('admin.breeds') ? 'active' : '' }}">
                    <i class="fas fa-paw"></i>
                    Dog Breed Management
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.emotions') }}" class="nav-link {{ request()->routeIs('admin.emotions') ? 'active' : '' }}">
                    <i class="fas fa-desktop"></i>
                    AI emotions Monitoring
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.voice-response') }}" class="nav-link {{ request()->routeIs('admin.voice-response') ? 'active' : '' }}">
                    <i class="fas fa-microphone"></i>
                    Voice Response Management
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.audio-logs') }}" class="nav-link {{ request()->routeIs('admin.audio-logs') ? 'active' : '' }}">
                    <i class="fas fa-microphone-alt"></i>
                    Audio Logs
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.history') }}" class="nav-link {{ request()->routeIs('admin.history') ? 'active' : '' }}">
                    <i class="fas fa-redo"></i>
                    History Module
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.multilingual') }}" class="nav-link {{ request()->routeIs('admin.multilingual') ? 'active' : '' }}">
                    <i class="fas fa-globe"></i>
                    Multilingual Configuration
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    App Settings
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    Payment & Subscription
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Advanced Analytics
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <a href="{{ route('admin.logout') }}" class="nav-link">
                    <i class="fas fa-arrow-left"></i>
                    Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Sample Data Generation Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Sample Data Generation Button (only show if no data) -->
        @if(!$totalUsers || $totalUsers == 0)
        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
            <div>
                <i class="fas fa-info-circle me-2"></i>
                <strong>No data available!</strong> Generate sample data to see the dashboard in action.
            </div>
            <a href="{{ route('admin.generate-sample-data') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-magic me-1"></i>
                Generate Sample Data
            </a>
        </div>
        @endif

        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0">Dashboard Overview</h2>
                <small class="text-muted">Real-time data from your dog translation app</small>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh Data
                </button>
                @if($totalUsers && $totalUsers > 0)
                <a href="{{ route('admin.generate-sample-data') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Add More Sample Data
                </a>
                @endif
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg">
                <div class="kpi-card">
                    <div class="kpi-title">Total Users</div>
                    @if($totalUsers)
                    <div class="kpi-value">{{ number_format($totalUsers) }}</div>
                    @else
                    <div class="kpi-value-empty">
                        <div class="kpi-empty-state">
                            <div class="kpi-empty-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="kpi-empty-text">No users yet</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg">
                <div class="kpi-card">
                    <div class="kpi-title">Active Users</div>
                    @if($activeUsers)
                    <div class="kpi-value">{{ number_format($activeUsers) }}</div>
                    @else
                    <div class="kpi-value-empty">
                        <div class="kpi-empty-state">
                            <div class="kpi-empty-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="kpi-empty-text">No active users</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg">
                <div class="kpi-card">
                    <div class="kpi-title">Premium Users</div>
                    @if($premiumUsers)
                    <div class="kpi-value">{{ number_format($premiumUsers) }}</div>
                    @else
                    <div class="kpi-value-empty">
                        <div class="kpi-empty-state">
                            <div class="kpi-empty-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="kpi-empty-text">No premium users</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg">
                <div class="kpi-card">
                    <div class="kpi-title">Total Detections</div>
                    @if($totalDetections)
                    <div class="kpi-value">{{ number_format($totalDetections) }}</div>
                    @else
                    <div class="kpi-value-empty">
                        <div class="kpi-empty-state">
                            <div class="kpi-empty-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="kpi-empty-text">No detections yet</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg">
                <div class="kpi-card">
                    <div class="kpi-title">Monthly Revenue</div>
                    @if($monthlyRevenue)
                    <div class="kpi-value">${{ number_format($monthlyRevenue, 2) }}</div>
                    @else
                    <div class="kpi-value-empty">
                        <div class="kpi-empty-state">
                            <div class="kpi-empty-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="kpi-empty-text">No revenue data</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Daily Detection Count</div>
                    @if($dailyDetectionGrowth)
                    <div class="chart-metric">{{ $dailyDetectionGrowth }} from last week</div>
                    <div class="chart-subtitle {{ $dailyDetectionGrowth >= '+0%' ? 'positive' : 'negative' }}">Last 7 Days {{ $dailyDetectionGrowth }}</div>
                    @else
                    <div class="chart-metric metric-empty">No data available</div>
                    <div class="chart-subtitle">Last 7 Days —</div>
                    @endif
                    <div class="mt-3" style="height: 200px;">
                        @if($dailyDetectionLabels && count($dailyDetectionLabels) > 0)
                        <canvas id="dailyDetectionChart"></canvas>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="empty-state-text">No Detection Data</div>
                            <div class="empty-state-subtext">Start monitoring to see daily trends</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Top Dog Breeds</div>
                    @if($topBreedsTotal)
                    <div class="chart-metric">{{ number_format($topBreedsTotal) }} detections</div>
                    <div class="chart-subtitle {{ $topBreedsGrowth && $topBreedsGrowth >= '+0%' ? 'positive' : 'negative' }}">Last 30 Days {{ $topBreedsGrowth }}</div>
                    @else
                    <div class="chart-metric metric-empty">No data available</div>
                    <div class="chart-subtitle">Last 30 Days —</div>
                    @endif
                    <div class="mt-3" style="height: 200px;">
                        @if($breedsLabels && count($breedsLabels) > 0)
                        <canvas id="dogBreedsChart"></canvas>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div class="empty-state-text">No Breed Data</div>
                            <div class="empty-state-subtext">Breed detections will appear here</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Emotion Distribution</div>
                    @if($emotionTotal)
                    <div class="chart-metric">{{ number_format($emotionTotal) }} detections</div>
                    <div class="chart-subtitle {{ $emotionGrowth && $emotionGrowth >= '+0%' ? 'positive' : 'negative' }}">Last 30 Days {{ $emotionGrowth }}</div>
                    @else
                    <div class="chart-metric metric-empty">No data available</div>
                    <div class="chart-subtitle">Last 30 Days —</div>
                    @endif
                    <div class="mt-3" style="height: 200px;">
                        @if($emotionLabels && count($emotionLabels) > 0)
                        <canvas id="emotionChart"></canvas>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-smile"></i>
                            </div>
                            <div class="empty-state-text">No Emotion Data</div>
                            <div class="empty-state-subtext">Emotion analysis will appear here</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-title">Top Languages</div>
                    @if($languagesTotal)
                    <div class="chart-metric">{{ number_format($languagesTotal) }} detections</div>
                    <div class="chart-subtitle {{ $languagesGrowth && $languagesGrowth >= '+0%' ? 'positive' : 'negative' }}">Last 30 Days {{ $languagesGrowth }}</div>
                    @else
                    <div class="chart-metric metric-empty">No data available</div>
                    <div class="chart-subtitle">Last 30 Days —</div>
                    @endif
                    <div class="mt-3" style="height: 200px;">
                        @if($languagesLabels && count($languagesLabels) > 0)
                        <canvas id="languagesChart"></canvas>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="empty-state-text">No Language Data</div>
                            <div class="empty-state-subtext">Language usage will appear here</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Hidden data inputs for charts -->
    <input type="hidden" id="dailyLabels" value="{{ json_encode($dailyDetectionLabels ?? []) }}">
    <input type="hidden" id="dailyData" value="{{ json_encode($dailyDetectionData ?? []) }}">
    <input type="hidden" id="breedsLabels" value="{{ json_encode($breedsLabels ?? []) }}">
    <input type="hidden" id="breedsData" value="{{ json_encode($breedsData ?? []) }}">
    <input type="hidden" id="emotionLabels" value="{{ json_encode($emotionLabels ?? []) }}">
    <input type="hidden" id="emotionData" value="{{ json_encode($emotionData ?? []) }}">
    <input type="hidden" id="languagesLabels" value="{{ json_encode($languagesLabels ?? []) }}">
    <input type="hidden" id="languagesData" value="{{ json_encode($languagesData ?? []) }}">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Daily Detection Chart
        const dailyLabels = JSON.parse(document.getElementById('dailyLabels').value);
        const dailyData = JSON.parse(document.getElementById('dailyData').value);

        if (dailyLabels.length > 0 && dailyData.length > 0) {
            const dailyCtx = document.getElementById('dailyDetectionChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Detections',
                        data: dailyData,
                        borderColor: '#58A509',
                        backgroundColor: 'rgba(88, 165, 9, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Dog Breeds Chart
        const breedsLabels = JSON.parse(document.getElementById('breedsLabels').value);
        const breedsData = JSON.parse(document.getElementById('breedsData').value);

        if (breedsLabels.length > 0 && breedsData.length > 0) {
            const breedsCtx = document.getElementById('dogBreedsChart').getContext('2d');
            new Chart(breedsCtx, {
                type: 'bar',
                data: {
                    labels: breedsLabels,
                    datasets: [{
                        label: 'Detections',
                        data: breedsData,
                        backgroundColor: '#58A509',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Emotion Chart
        const emotionLabels = JSON.parse(document.getElementById('emotionLabels').value);
        const emotionData = JSON.parse(document.getElementById('emotionData').value);

        if (emotionLabels.length > 0 && emotionData.length > 0) {
            const emotionCtx = document.getElementById('emotionChart').getContext('2d');
            new Chart(emotionCtx, {
                type: 'bar',
                data: {
                    labels: emotionLabels,
                    datasets: [{
                        label: 'Detections',
                        data: emotionData,
                        backgroundColor: '#58A509',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Languages Chart
        const languagesLabels = JSON.parse(document.getElementById('languagesLabels').value);
        const languagesData = JSON.parse(document.getElementById('languagesData').value);

        if (languagesLabels.length > 0 && languagesData.length > 0) {
            const languagesCtx = document.getElementById('languagesChart').getContext('2d');
            new Chart(languagesCtx, {
                type: 'bar',
                data: {
                    labels: languagesLabels,
                    datasets: [{
                        label: 'Detections',
                        data: languagesData,
                        backgroundColor: '#58A509',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Refresh dashboard function
        function refreshDashboard() {
            location.reload();
        }
    </script>
</body>

</html>