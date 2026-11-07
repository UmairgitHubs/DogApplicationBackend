<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Emotions Monitoring - DogTranslation.com Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
            overflow-x: auto;
            max-width: calc(100vw - 280px);
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

        .content-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            overflow: visible;
        }

        .metric-card {
            background: #f0f9e8;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            color: var(--primary-green);
            font-size: 2rem;
            font-weight: 700;
        }

        .chart-container {
            margin-top: 1.5rem;
            height: 300px;
            position: relative;
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .detection-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .detection-table thead {
            background-color: #f9fafb;
            border-bottom: 2px solid var(--card-border);
        }

        .detection-table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .detection-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--primary-green);
            white-space: nowrap;
        }

        .detection-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .detection-table {
                min-width: 500px;
            }
        }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">AI Emotions Monitoring</h1>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">Total Detections Today</div>
                    <div class="metric-value">{{ number_format($detectionsToday ?? 0) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">Total Detections This Week</div>
                    <div class="metric-value">{{ number_format($detectionsThisWeek ?? 0) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">Total Detections This Month</div>
                    <div class="metric-value">{{ number_format($detectionsThisMonth ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Emotion Distribution Chart -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">Emotion Distribution</h2>
                    <div class="chart-container">
                        <canvas id="emotionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Detection Logs -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">Recent Detection Logs</h2>
                    <div class="table-responsive">
                        <table class="detection-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Dog</th>
                                <th>Emotion</th>
                                <th>Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs ?? [] as $log)
                            <tr>
                                <td>{{ $log['timestamp']->format('Y-m-d h:i A') }}</td>
                                <td>{{ $log['user_name'] }}</td>
                                <td>{{ $log['dog_name'] }}</td>
                                <td>{{ $log['emotion'] }}</td>
                                <td>{{ $log['confidence'] }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No detection logs available</td>
                            </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Emotion distribution data
        const emotionData = @json($topEmotions ?? []);
        
        // Filter out emotions with 0 count and prepare chart data
        const filteredEmotions = Object.entries(emotionData)
            .filter(([emotion, count]) => count > 0)
            .sort((a, b) => b[1] - a[1]); // Sort by count descending
        
        const emotions = filteredEmotions.map(([emotion]) => emotion);
        const counts = filteredEmotions.map(([emotion, count]) => count);
        
        // Initialize chart
            const ctx = document.getElementById('emotionChart').getContext('2d');
        const maxCount = Math.max(...counts, 1);
        
        const emotionChart = new Chart(ctx, {
            type: 'bar',
                data: {
                labels: emotions,
                    datasets: [{
                    label: 'Detection Count',
                    data: counts,
                    backgroundColor: 'rgba(88, 165, 9, 0.2)',
                    borderColor: 'rgba(88, 165, 9, 0.8)',
                        borderWidth: 2,
                    borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                maintainAspectRatio: false,
                    plugins: {
                        legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: maxCount + 5,
                        ticks: {
                            stepSize: 5,
                            color: '#6b7280'
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
