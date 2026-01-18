<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logoPNL.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logoPNL.png') }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Đăng nhập') | Võ Thuật Việt Nam</title>
    <meta name="description"
        content="Võ Thuật Việt Nam - Hệ thống quản lý võ đường chuyên nghiệp, hiện đại và tiện lợi">
    <meta name="keywords" content="võ thuật, quản lý võ đường, martial arts, VoThuatVN">
    <meta name="author" content="Võ Thuật Việt Nam">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="VoThuatVN - Hệ thống quản lý võ đường">
    <meta property="og:description" content="Hệ thống quản lý võ đường chuyên nghiệp">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS - Animate On Scroll Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Google Fonts - Roboto & Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-red: #dc2626;
            --primary-red-dark: #b91c1c;
            --primary-red-light: #ef4444;
            --light-bg-1: #f8fafc;
            --light-bg-2: #ffffff;
            --light-bg-3: #f1f5f9;
            --light-bg-4: #e2e8f0;
            --text-dark: #0f172a;
            --text-gray: #475569;
            --text-gray-light: #64748b;
            --text-gray-dark: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            background: var(--light-bg-1);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Video Background Container */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .video-background video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            opacity: 0.4;
        }

        /* Light Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.95) 0%, rgba(255, 255, 255, 0.92) 100%);
            z-index: 1;
        }

        /* Main Content */
        .auth-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Logo & Brand */
        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo h1 {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text-dark) 0%, var(--primary-red-light) 50%, var(--text-dark) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 3px;
            margin-bottom: 0.5rem;
            animation: textGlow 3s ease-in-out infinite, gradientShift 5s ease infinite;
            text-shadow: 0 0 20px rgba(220, 38, 38, 0.2);
            filter: drop-shadow(0 0 5px rgba(220, 38, 38, 0.1));
        }

        .brand-logo p {
            color: var(--text-gray);
            font-size: 1rem;
            font-weight: 400;
            letter-spacing: 1px;
        }

        .auth-logo {
            width: 180px;
            height: 180px;
            object-fit: contain;
            filter: brightness(1.5) contrast(1.2) drop-shadow(0 0 15px rgba(255, 255, 255, 0.9)) drop-shadow(0 0 35px rgba(255, 50, 50, 0.7)) drop-shadow(0 0 60px rgba(255, 255, 255, 0.4));
            animation: authPulse 4s infinite ease-in-out;
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text-dark) 0%, #ff3333 50%, var(--text-dark) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
            animation: textGradientShift 5s infinite linear;
            text-shadow: 0 0 15px rgba(255, 50, 50, 0.4), 0 0 30px rgba(255, 50, 50, 0.2);
        }

        @keyframes textGradientShift {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 200% 50%;
            }
        }

        @keyframes authPulse {
            0% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(220, 38, 38, 0.3));
            }

            50% {
                transform: scale(1.03);
                filter: drop-shadow(0 0 25px rgba(220, 38, 38, 0.6));
            }

            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(220, 38, 38, 0.3));
            }
        }

        /* Auth Card - Light Theme */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 40px rgba(220, 38, 38, 0.05);
            max-width: 450px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15), 0 0 50px rgba(220, 38, 38, 0.1);
        }

        .auth-card h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
            color: var(--text-dark);
        }

        .auth-card .subtitle {
            text-align: center;
            color: var(--text-gray-light);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        /* Form Styling */
        .form-label {
            color: var(--text-gray);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .form-label:hover {
            color: var(--text-white);
        }

        .form-label .required {
            color: var(--primary-red);
            font-weight: 700;
        }

        .form-control {
            background: var(--light-bg-2);
            border: 1px solid var(--light-bg-4);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:hover {
            border-color: var(--primary-red-light);
            background: var(--light-bg-3);
        }

        .form-control:focus {
            background: var(--light-bg-2);
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1), 0 0 15px rgba(220, 38, 38, 0.1);
            color: var(--text-dark);
            outline: none;
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-gray-dark);
        }

        .form-control.is-invalid {
            border-color: var(--primary-red);
            animation: shake 0.5s ease;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.3);
        }

        /* Primary Button */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.85rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: #ffffff;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.6);
            background: linear-gradient(135deg, var(--primary-red-light) 0%, var(--primary-red) 100%);
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom:active {
            transform: translateY(0) scale(0.98);
        }

        /* Links */
        .auth-link {
            color: var(--text-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
        }

        .auth-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-red);
            transition: width 0.3s ease;
        }

        .auth-link:hover {
            color: var(--primary-red);
            text-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
        }

        .auth-link:hover::after {
            width: 100%;
        }

        /* Alert Messages */
        .alert {
            border-radius: 10px;
            border: none;
            animation: slideInDown 0.5s ease;
        }

        .alert-danger {
            background: #dc2626;
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
        }

        .alert-success {
            background: #22c55e;
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
        }

        /* Checkbox */
        .form-check-input {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:hover {
            border-color: var(--primary-red);
        }

        .form-check-input:checked {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
        }

        .form-check-label {
            color: var(--text-gray);
            cursor: pointer;
            user-select: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .form-check-label:hover {
            color: var(--text-dark);
        }

        /* Password Toggle Icon */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-gray-dark);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-red);
            transform: translateY(-50%) scale(1.2);
        }

        .password-wrapper {
            position: relative;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes textGlow {

            0%,
            100% {
                text-shadow: 0 0 10px rgba(220, 38, 38, 0.3), 0 0 20px rgba(220, 38, 38, 0.2);
            }

            50% {
                text-shadow: 0 0 20px rgba(220, 38, 38, 0.6), 0 0 30px rgba(220, 38, 38, 0.4);
            }
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .brand-logo h1 {
                font-size: 2rem;
            }

            .auth-card h2 {
                font-size: 1.5rem;
            }

            .btn-primary-custom {
                padding: 0.75rem 1.5rem;
            }
        }

        @media (max-width: 400px) {
            .brand-logo h1 {
                font-size: 1.75rem;
            }

            .auth-card {
                padding: 1.5rem 1rem;
            }
        }

        /* Error Messages */
        .invalid-feedback {
            display: block;
            color: #fca5a5;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            animation: fadeInDown 0.3s ease;
        }

        /* Helper Text */
        .form-text {
            color: var(--text-gray-dark);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Video Background -->
    <div class="video-background">
        <!-- Uncomment và thêm video khi có file video -->
        <!--
        <video autoplay muted loop playsinline>
            <source src="/videos/martial-arts-background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        -->
    </div>

    <!-- Dark Overlay -->
    <div class="overlay"></div>

    <!-- Main Auth Container -->
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Brand Logo -->
            <div class="brand-logo" data-aos="fade-down" data-aos-duration="800">
                <img src="{{ asset('images/logoPNL.png') }}" alt="Logo" class="auth-logo mb-3">
                <h1 class="brand-name">Võ Thuật Việt Nam</h1>
                <p>Hệ thống quản lý võ đường chuyên nghiệp</p>
            </div>

            <!-- Auth Card -->
            <div class="auth-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS - Animate On Scroll -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 50
        });
    </script>

    @yield('scripts')
</body>

</html>