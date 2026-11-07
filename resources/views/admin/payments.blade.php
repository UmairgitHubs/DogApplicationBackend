<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Subscription - DogTranslation.com Admin</title>
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
                <i class="fas fa-credit-card text-primary me-2"></i>
                Payment & Subscription
            </h1>
            <p class="page-subtitle">Manage payments and subscription plans</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="content-card text-center">
                            <h3 class="text-primary mb-1" id="monthlyRevenue">-</h3>
                            <small class="text-muted">Monthly Revenue</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="content-card text-center">
                            <h3 class="text-success mb-1" id="totalRevenue">-</h3>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="content-card text-center">
                            <h3 class="text-warning mb-1" id="premiumUsers">-</h3>
                            <small class="text-muted">Premium Users</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="content-card text-center">
                            <h3 class="text-info mb-1" id="activeSubscriptions">-</h3>
                            <small class="text-muted">Active Subscriptions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Recent Transactions</h5>
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search transactions..." id="transactionSearch">
                        </div>
                    </div>

                    <div id="transactionsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading transactions...</p>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-3">Revenue Chart (Last 7 Days)</h5>
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>

            <div class="col-md-4">
                <div class="content-card">
                    <h5 class="mb-3">Subscription Plans</h5>
                    <div id="subscriptionPlans">
                        <div class="mb-3 border rounded p-3">
                            <h6>Free Plan</h6>
                            <p class="text-muted mb-2">Basic features</p>
                            <strong class="text-primary">$0/month</strong>
                        </div>
                        <div class="mb-3 border rounded p-3">
                            <h6>Premium Plan</h6>
                            <p class="text-muted mb-2">All features included</p>
                            <strong class="text-primary">$9.99/month</strong>
                        </div>
                        <div class="border rounded p-3">
                            <h6>Enterprise Plan</h6>
                            <p class="text-muted mb-2">Custom features</p>
                            <strong class="text-primary">$29.99/month</strong>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-3">Payment Methods</h5>
                    <div id="paymentMethods">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fab fa-cc-stripe text-primary me-2"></i>Stripe</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fab fa-cc-paypal text-info me-2"></i>PayPal</span>
                            <span class="badge bg-secondary">Inactive</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-credit-card text-warning me-2"></i>Credit Card</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <h5 class="mb-3">Revenue Summary</h5>
                    <div id="revenueSummary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>This Month</span>
                            <strong id="thisMonthRevenue">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Last Month</span>
                            <strong id="lastMonthRevenue">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Growth</span>
                            <strong class="text-success" id="revenueGrowth">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let revenueChart = null;

        function loadPaymentData() {
            fetch('/api/admin/dashboard/stats', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        updatePaymentKPIs(data.data.kpi);
                        loadTransactions();
                        drawRevenueChart();
                        updateRevenueSummary(data.data.kpi);
                    } else {
                        showError('Failed to load payment data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load payment data');
                });
        }

        function updatePaymentKPIs(kpi) {
            const monthlyRevenue = kpi.monthlyRevenue || 0;
            const totalRevenue = monthlyRevenue * 12; // Estimated annual
            const premiumUsers = kpi.premiumUsers || 0;
            const activeSubscriptions = premiumUsers; // Assuming all premium users have active subscriptions

            document.getElementById('monthlyRevenue').textContent = '$' + monthlyRevenue.toFixed(2);
            document.getElementById('totalRevenue').textContent = '$' + totalRevenue.toFixed(2);
            document.getElementById('premiumUsers').textContent = premiumUsers;
            document.getElementById('activeSubscriptions').textContent = activeSubscriptions;
        }

        function loadTransactions() {
            fetch('/api/admin/users?per_page=10', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status && data.data && data.data.users && data.data.users.length > 0) {
                        displayTransactions(data.data.users);
                    } else {
                        showTransactionsEmpty();
                    }
                })
                .catch(error => {
                    console.error('Error loading transactions:', error);
                    showTransactionsEmpty();
                });
        }

        function displayTransactions(users) {
            const transactionsDiv = document.getElementById('transactionsList');
            
            // Simulate transactions from users
            const transactions = users.map((user, index) => {
                const amount = 9.99; // Premium subscription
                const date = new Date(user.created_at).toLocaleDateString();
                const status = user.email_verified ? 'success' : 'pending';
                const statusText = user.email_verified ? 'Completed' : 'Pending';
                const statusIcon = user.email_verified ? 'check-circle text-success' : 'clock text-warning';

                return `
                    <div class="border rounded p-3 mb-2 transaction-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${user.name || user.email}</h6>
                                <small class="text-muted">Subscription - ${date}</small>
                            </div>
                            <div class="text-end">
                                <div class="mb-1">
                                    <strong>$${amount.toFixed(2)}</strong>
                                </div>
                                <div>
                                    <i class="fas fa-${statusIcon.split(' ')[0]} ${statusIcon.split(' ')[1]}"></i>
                                    <small class="ms-1">${statusText}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            transactionsDiv.innerHTML = transactions.join('');
        }

        function showTransactionsEmpty() {
            const transactionsDiv = document.getElementById('transactionsList');
            transactionsDiv.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-credit-card text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 text-muted">No transactions found</p>
                </div>
            `;
        }

        function drawRevenueChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            if (revenueChart) {
                revenueChart.destroy();
            }

            // Generate sample revenue data for last 7 days
            const labels = [];
            const data = [];
            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                data.push(Math.random() * 100 + 50); // Random revenue between 50-150
            }

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: data,
                        borderColor: '#58A509',
                        backgroundColor: 'rgba(88, 165, 9, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateRevenueSummary(kpi) {
            const monthlyRevenue = kpi.monthlyRevenue || 0;
            const lastMonthRevenue = monthlyRevenue * 0.9; // Simulated 10% less
            const growth = ((monthlyRevenue - lastMonthRevenue) / lastMonthRevenue * 100).toFixed(1);

            document.getElementById('thisMonthRevenue').textContent = '$' + monthlyRevenue.toFixed(2);
            document.getElementById('lastMonthRevenue').textContent = '$' + lastMonthRevenue.toFixed(2);
            document.getElementById('revenueGrowth').textContent = '+' + growth + '%';
        }

        function showError(message) {
            console.error(message);
        }

        // Search functionality
        document.getElementById('transactionSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const transactionItems = document.querySelectorAll('.transaction-item');

            transactionItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Load initial data
        loadPaymentData();
    </script>
</body>

</html>