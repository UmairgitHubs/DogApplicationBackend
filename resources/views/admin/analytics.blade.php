<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Analytics - DogTranslation.com Admin</title>
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
        }

        .metric-card {
            background: #f0f9e8;
            border: 1px solid var(--card-border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .metric-growth {
            color: var(--primary-green);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 1.5rem;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
        }

        .stats-change {
            font-size: 0.875rem;
            color: var(--primary-green);
            font-weight: 500;
        }

        .chart-container {
            margin-top: 1.5rem;
            height: 300px;
            position: relative;
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
            <h1 class="page-title">Advanced Analytics</h1>
        </div>

        <!-- Key Metrics Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">Total Users</div>
                    <div class="metric-value">{{ number_format($totalUsers ?? 12345) }}</div>
                    <div class="metric-growth">+{{ $userGrowth ?? 10 }}%</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">Premium Conversion Rate</div>
                    <div class="metric-value">{{ $premiumConversionRate ?? 5.2 }}%</div>
                    <div class="metric-growth">+{{ $premiumGrowth ?? 2 }}%</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-label">ARPU (Average Revenue Per User)</div>
                    <div class="metric-value">${{ number_format($arpu ?? 12.50, 2) }}</div>
                    <div class="metric-growth">+{{ $arpuGrowth ?? 5 }}%</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Growth -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">User Growth</h2>
                    <div class="stats-header">
                        <div>
                            <div class="stats-value">+{{ $userGrowth ?? 15 }}%</div>
                            <div class="stats-change">Last 30 Days +{{ $userGrowth ?? 15 }}%</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Dog Breeds -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">Top Dog Breeds</h2>
                    <div class="stats-header">
                        <div>
                            <div class="stats-value">100%</div>
                            <div class="stats-change">Last 30 Days +5%</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="breedsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Emotion Distribution -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">Emotion Distribution</h2>
                    <div class="stats-header">
                        <div>
                            <div class="stats-value">100%</div>
                            <div class="stats-change">Last 30 Days +2%</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="emotionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-md-6">
                <div class="content-card">
                    <h2 class="section-title">Monthly Revenue</h2>
                    <div class="stats-header">
                        <div>
                            <div class="stats-value">+8%</div>
                            <div class="stats-change">Last 12 Months +8%</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        @php
            $monthsData = $months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            $userGrowthDataArray = $userGrowthData ?? [];
        @endphp
        const months = @json($monthsData);
        const userGrowthData = @json($userGrowthDataArray);
        const userGrowthChart = new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'User Growth',
                    data: userGrowthData,
                    borderColor: 'rgba(88, 165, 9, 1)',
                    backgroundColor: 'rgba(88, 165, 9, 0.1)',
                    tension: 0.4,
                    fill: true
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
                        beginAtZero: false,
                        ticks: {
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

        // Top Dog Breeds Chart (Horizontal Bar)
        const breedsCtx = document.getElementById('breedsChart').getContext('2d');
        @php
            $breedNamesArray = $breedNames ?? [];
            $breedCountsArray = $breedCounts ?? [];
        @endphp
        const breedNames = @json($breedNamesArray);
        const breedCounts = @json($breedCountsArray);
        const breedsChart = new Chart(breedsCtx, {
            type: 'bar',
            data: {
                labels: breedNames,
                datasets: [{
                    label: 'Usage Count',
                    data: breedCounts,
                    backgroundColor: 'rgba(88, 165, 9, 0.2)',
                    borderColor: 'rgba(88, 165, 9, 0.8)',
                    borderWidth: 2,
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
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    y: {
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

        // Emotion Distribution Chart (Vertical Bar)
        const emotionCtx = document.getElementById('emotionChart').getContext('2d');
        @php
            $emotionDataArray = $emotionData ?? [];
        @endphp
        const emotionData = @json($emotionDataArray);
        const emotionChart = new Chart(emotionCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(emotionData),
                datasets: [{
                    label: 'Distribution',
                    data: Object.values(emotionData),
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
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

        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        @php
            $revenueDataArray = $revenueData ?? [];
        @endphp
        const revenueData = @json($revenueDataArray);
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: revenueData,
                    borderColor: 'rgba(88, 165, 9, 1)',
                    backgroundColor: 'rgba(88, 165, 9, 0.1)',
                    tension: 0.4,
                    fill: true
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
                        beginAtZero: false,
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
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
