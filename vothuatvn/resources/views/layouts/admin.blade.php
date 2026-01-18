<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Hệ thống quản lý võ thuật VoThuatVN - Quản lý võ sinh, lớp học, huấn luyện viên">
    <meta name="keywords" content="võ thuật, quản lý võ sinh, martial arts">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logoPNL.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logoPNL.png') }}">

    <title>@yield('title', 'Admin Dashboard') | Võ Thuật Việt Nam</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

    <style>
        /* Logo Styles */
        .sidebar-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            /* Phối hợp độ sáng cực mạnh và hào quang trắng để nổi bật trên nền tối */
            filter: brightness(1.5) contrast(1.2) drop-shadow(0 0 10px rgba(255, 255, 255, 0.9)) drop-shadow(0 0 25px rgba(255, 50, 50, 0.7)) drop-shadow(0 0 45px rgba(255, 255, 255, 0.3));
            transition: all 0.4s ease;
        }

        .sidebar-logo:hover {
            filter: brightness(1.8) contrast(1.3) drop-shadow(0 0 15px rgba(255, 255, 255, 1)) drop-shadow(0 0 30px rgba(255, 50, 50, 0.9));
            transform: scale(1.1);
        }

        .animate-pulse-glow {
            animation: pulse-glow 3s infinite ease-in-out;
        }

        @keyframes pulse-glow {
            0% {
                filter: drop-shadow(0 0 3px rgba(220, 38, 38, 0.2));
            }

            50% {
                filter: drop-shadow(0 0 12px rgba(220, 38, 38, 0.5));
            }

            100% {
                filter: drop-shadow(0 0 3px rgba(220, 38, 38, 0.2));
            }
        }

        /* Enhanced Sidebar Styles - USE CSS VARIABLES */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--color-bg-2);
            border-right: 1px solid var(--color-border);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(220, 38, 38, 0.05);
        }

        .sidebar-header h3 {
            color: #dc2626;
            font-weight: 800;
            font-size: 1.75rem;
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header small {
            color: var(--color-text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            font-weight: 500;
            font-size: 0.9375rem;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.15) 0%, transparent 100%);
            transition: width 0.3s ease;
        }

        .menu-item:hover::before {
            width: 100%;
        }

        .menu-item:hover {
            background: rgba(220, 38, 38, 0.08);
            color: var(--color-text-primary);
            border-left-color: #dc2626;
            padding-left: 1.75rem;
        }

        .menu-item.active {
            background: rgba(220, 38, 38, 0.15);
            color: #dc2626;
            border-left-color: #dc2626;
            font-weight: 600;
            box-shadow: inset 0 0 20px rgba(220, 38, 38, 0.1);
        }

        .menu-item i {
            width: 24px;
            margin-right: 0.875rem;
            font-size: 1.125rem;
            transition: transform 0.25s ease;
        }

        .menu-item:hover i {
            transform: scale(1.1);
        }

        .menu-item.active i {
            color: #dc2626;
            animation: pulse 2s infinite;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--color-bg-1);
        }

        /* Top Navbar - USE CSS VARIABLES */
        .top-navbar {
            background: var(--color-bg-3);
            padding: 1.25rem 2rem;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .top-navbar h4 {
            font-weight: 700;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            color: var(--color-text-secondary);
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .content-area {
            padding: 2rem;
            animation: fadeIn 0.5s ease;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #dc2626;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(220, 38, 38, 0.2);
            transform: scale(1.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            /* Prevent blocking when not active */
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
            /* Enable clicking to close menu */
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .sidebar-overlay {
                display: none;
                /* CRITICAL: Changed from block to none */
            }

            .sidebar-overlay.active {
                display: block;
            }

            .content-area {
                padding: 1.5rem;
            }
        }

        @media (max-width: 767px) {
            .top-navbar {
                padding: 1rem 1.5rem;
            }

            .top-navbar h4 {
                font-size: 1.125rem;
            }

            .user-name {
                display: none;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Alert Animations */
        .alert {
            animation: slideInRight 0.4s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* SweetAlert2 Custom Styling */
        .swal-custom-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }

        .swal-custom-title {
            color: var(--color-text-primary) !important;
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem !important;
        }

        .swal-custom-text {
            color: var(--color-text-secondary) !important;
            font-size: 1rem !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }

        .swal2-actions {
            gap: 0.75rem !important;
            margin-top: 1.5rem !important;
        }

        /* Dark theme support */
        html[data-theme="dark"] .swal2-popup {
            background: var(--color-bg-2) !important;
        }

        html[data-theme="dark"] .swal2-title {
            color: var(--color-text-primary) !important;
        }

        html[data-theme="dark"] .swal2-html-container {
            color: var(--color-text-secondary) !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    @if(Auth::check() && Auth::user()->role === 'coach')
        @include('partials.sidebar-coach')
    @else
        @include('partials.sidebar-admin')
    @endif

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            <div class="user-info">
                <!-- Theme Toggle Button -->
                <button class="theme-toggle" id="theme-toggle" title="Chuyển đổi giao diện">
                    <i class="bi bi-sun-fill" id="theme-icon"></i>
                </button>

                <span class="user-name">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle me-1"
                            style="width: 24px; height: 24px; object-fit: cover; border: 1px solid var(--color-primary);">
                    @else
                        <i class="bi bi-person-circle fs-5 me-1"></i>
                    @endif
                    {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Delete Confirmation Helper -->
    <script>
        /**
         * Modern confirmation dialog for delete actions
         * @param {string} message - Custom message to display
         * @param {string} title - Dialog title (optional)
         * @returns {Promise<boolean>} - True if confirmed, false if cancelled
         */
        async function confirmDelete(message = 'Bạn có chắc muốn xóa mục này?', title = 'Xác nhận xóa?') {
            const result = await Swal.fire({
                title: title,
                html: `<p class="text-muted mb-0">${message}</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Xác nhận',
                cancelButtonText: '<i class="bi bi-x-lg me-1"></i> Huỷ',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    htmlContainer: 'swal-custom-text',
                    confirmButton: 'btn btn-danger px-4 py-2 fw-semibold',
                    cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold'
                },
                buttonsStyling: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            });
            return result.isConfirmed;
        }

        /**
         * Handle delete form submission with confirmation
         * @param {HTMLElement} button - The button that triggered the action
         * @param {string} message - Confirmation message
         */
        async function handleDeleteForm(button, message) {
            const confirmed = await confirmDelete(message);
            if (confirmed) {
                button.closest('form').submit();
            }
        }
    </script>

    <!-- Custom Admin Scripts -->
    <script src="{{ asset('js/admin-scripts.js') }}"></script>
    @yield('scripts')
    @stack('modals')
    @stack('scripts')
</body>

</html>