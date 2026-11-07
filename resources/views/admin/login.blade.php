<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogTranslation.com - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .popup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            max-height: 600px;
        }

        .form-section {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            width: 50%;
            position: relative;
        }

        .page-header {
            position: absolute;
            top: 25px;
            left: 40px;
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            margin-top: 40px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control {
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #e8f5e8;
            color: #333;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            background-color: #d4edda;
            box-shadow: 0 0 0 2px rgba(88, 165, 9, 0.25);
        }

        .form-control::placeholder {
            color: #58A509;
            font-weight: 400;
        }

        .btn-login {
            background: #58A509;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 15px;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #4a8a07;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(88, 165, 9, 0.3);
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #333;
        }

        .register-link a {
            color: #58A509;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 6px 12px;
            border: 2px solid #58A509;
            border-radius: 15px;
            display: inline-block;
            background: white;
            font-size: 12px;
        }

        .register-link a:hover {
            color: white;
            background: #58A509;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(88, 165, 9, 0.3);
        }

        .image-section {
            background: #f5f5dc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            width: 50%;
            position: relative;
        }

        .dog-image {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .popup-container {
                flex-direction: column;
                max-height: none;
            }

            .form-section {
                padding: 25px 20px;
                width: 100%;
                order: 2;
            }

            .image-section {
                padding: 20px;
                width: 100%;
                order: 1;
                min-height: 200px;
            }

            .dog-image {
                max-height: 180px;
            }

            .page-header {
                position: static;
                text-align: center;
                margin-bottom: 15px;
                font-size: 18px;
            }

            .form-title {
                font-size: 20px;
                margin-bottom: 20px;
                margin-top: 15px;
            }

            .form-control {
                padding: 10px 12px;
                font-size: 13px;
            }

            .btn-login {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .form-section {
                padding: 20px 15px;
            }

            .image-section {
                padding: 15px;
                min-height: 150px;
            }

            .dog-image {
                max-height: 120px;
            }

            .form-title {
                font-size: 18px;
            }

            .page-header {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="popup-container">
        <!-- Form Section -->
        <div class="form-section">
            <h2 class="page-header">DogTranslation.com</h2>
            <h1 class="form-title">Admin Login</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    Login
                </button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="{{ route('admin.register') }}">Register</a>
            </div>
        </div>

        <!-- Image Section -->
        <div class="image-section">
            <img src="{{ asset('images/dog-illustration.png') }}" alt="Dog Illustration" class="dog-image">
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Add Bootstrap validation styles
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>
</body>

</html>