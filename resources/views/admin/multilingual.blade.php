<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multilingual Configuration - DogTranslation.com Admin</title>
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

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 1.5rem;
        }

        .language-table {
            width: 100%;
            border-collapse: collapse;
        }

        .language-table thead {
            background-color: #f9fafb;
            border-bottom: 2px solid var(--card-border);
        }

        .language-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-bg);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .language-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--dark-bg);
        }

        .language-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-true {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-false {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .file-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }

        .file-link:hover {
            color: #4a8a08;
            text-decoration: underline;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .stats-value {
            font-size: 2rem;
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

        .kpi-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .kpi-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-bg);
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
            <h1 class="page-title">Multilingual Configuration</h1>
        </div>

        <!-- Supported Languages Section -->
        <div class="content-card">
            <h2 class="section-title">Supported Languages</h2>
            <table class="language-table">
                <thead>
                    <tr>
                        <th>Language</th>
                        <th>Status</th>
                        <th>Translation File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($languages ?? [] as $language)
                    <tr>
                        <td><strong>{{ $language->language }}</strong></td>
                        <td>
                            <span class="status-badge {{ $language->status ? 'status-true' : 'status-false' }}">
                                {{ $language->status ? 'true' : 'false' }}
                            </span>
                        </td>
                        <td>
                            @if($language->status && $language->translation_file)
                                <a href="#" class="file-link" onclick="updateTranslationFile('{{ $language->code }}')">Update</a>
                            @else
                                <a href="#" class="file-link" onclick="uploadTranslationFile('{{ $language->code }}')">Upload</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">No languages found. Please seed the database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row">
            <!-- Language Usage Statistics -->
            <div class="col-md-8">
                <div class="content-card">
                    <h2 class="section-title">Language Usage Statistics</h2>
                    <div class="stats-header">
                        <div>
                            <div class="stats-value">100%</div>
                            <div class="stats-change">Last 30 Days +10%</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="languageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Key Performance Indicators -->
            <div class="col-md-4">
                <div class="content-card">
                    <h2 class="section-title">Key Performance Indicators</h2>
                    <div class="kpi-card mb-3">
                        <div class="kpi-label">Supported Languages</div>
                        <div class="kpi-value">{{ $totalSupportedLanguages ?? 5 }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Multilingual Users</div>
                        <div class="kpi-value">{{ number_format($multilingualUsers ?? 1200) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Language usage data
        const languageStats = @json($languageStats ?? []);
        
        // Prepare chart data
        const languages = Object.keys(languageStats);
        const counts = Object.values(languageStats);
        const maxCount = Math.max(...counts, 1);
        
        // Initialize horizontal bar chart
        const ctx = document.getElementById('languageChart').getContext('2d');
        const languageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: languages,
                datasets: [{
                    label: 'Usage Count',
                    data: counts,
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
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: maxCount + 50,
                        ticks: {
                            stepSize: 100,
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

        function updateTranslationFile(code) {
            alert('Update translation file functionality - To be implemented for: ' + code);
        }

        function uploadTranslationFile(code) {
            alert('Upload translation file functionality - To be implemented for: ' + code);
        }
    </script>
</body>

</html>
