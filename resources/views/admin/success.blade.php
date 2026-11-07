<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogTranslation.com - Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #58A509;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        .success-icon i {
            color: white;
            font-size: 40px;
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .success-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .loader-container {
            margin: 30px 0;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #58A509;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        .redirect-text {
            font-size: 14px;
            color: #888;
            margin-top: 20px;
        }

        .countdown {
            font-weight: 600;
            color: #58A509;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(88, 165, 9, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(88, 165, 9, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(88, 165, 9, 0);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: #f3f3f3;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
        }

        .progress-fill {
            height: 100%;
            background: #58A509;
            width: 0%;
            transition: width 0.1s ease;
            border-radius: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-container {
                padding: 40px 20px;
                margin: 10px;
            }

            .success-title {
                font-size: 24px;
            }

            .success-message {
                font-size: 14px;
            }

            .success-icon {
                width: 60px;
                height: 60px;
            }

            .success-icon i {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1 class="success-title">{{ $title ?? 'Success!' }}</h1>

        <p class="success-message">{{ $message ?? 'Operation completed successfully!' }}</p>

        <div class="loader-container">
            <div class="loader"></div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <p class="redirect-text">
            Redirecting to dashboard in <span class="countdown" id="countdown">3</span> seconds...
        </p>
    </div>

    <script>
        // Countdown and redirect functionality
        let countdown = 3;
        const countdownElement = document.getElementById('countdown');
        const progressFill = document.getElementById('progressFill');
        const totalTime = 3000; // 3 seconds
        const interval = 100; // Update every 100ms
        const steps = totalTime / interval;
        let currentStep = 0;

        // Update progress bar
        const progressInterval = setInterval(() => {
            currentStep++;
            const progress = (currentStep / steps) * 100;
            progressFill.style.width = progress + '%';
        }, interval);

        // Update countdown
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                clearInterval(progressInterval);
                window.location.href = '{{ route("admin.dashboard") }}';
            }
        }, 1000);

        // Fallback redirect after 3.5 seconds
        setTimeout(() => {
            window.location.href = '{{ route("admin.dashboard") }}';
        }, 3500);
    </script>
</body>

</html>