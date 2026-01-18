<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dashboard - VoThuatVN</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-red: #dc2626;
            --dark-bg-1: #0f172a;
            --dark-bg-2: #1e293b;
            --dark-bg-3: #334155;
            --text-white: #ffffff;
            --text-gray: #cbd5e1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg-1) 0%, var(--dark-bg-2) 100%);
            color: var(--text-white);
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-header {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: var(--text-gray);
            margin-bottom: 0;
        }

        .welcome-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .welcome-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .welcome-card .user-name {
            color: var(--primary-red);
            font-weight: 700;
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--primary-red) 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: var(--text-white);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
        }

        .info-text {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .welcome-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Dashboard</h1>
                    <p>Chào mừng bạn đến với VoThuatVN</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success mb-4" style="background: rgba(34, 197, 94, 0.2); color: #86efac; border: none; border-radius: 10px; border-left: 4px solid #22c55e;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2>
                Xin chào, <span class="user-name">{{ Auth::user()->name }}</span>! 👋
            </h2>
            <p class="info-text">
                Email: {{ Auth::user()->email }}
            </p>
            <p class="info-text mt-3">
                Bạn đã đăng nhập thành công vào hệ thống quản lý võ đường VoThuatVN.
            </p>
            <p class="info-text">
                Nội dung dashboard sẽ được phát triển thêm trong các phiên bản tiếp theo.
            </p>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide success messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>


