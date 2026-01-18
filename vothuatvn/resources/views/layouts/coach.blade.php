<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Coach Dashboard') - VoThuatVN</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

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
            background: var(--dark-bg-1);
            color: var(--text-white);
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--dark-bg-2);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: var(--primary-red);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(220, 38, 38, 0.1);
            color: var(--text-white);
            border-left-color: var(--primary-red);
        }

        .menu-item.active {
            background: rgba(220, 38, 38, 0.2);
            color: var(--primary-red);
            border-left-color: var(--primary-red);
        }

        .menu-item i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .top-navbar {
            background: var(--dark-bg-2);
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('partials.sidebar-coach')

    <div class="main-content">
        <div class="top-navbar">
            <div>
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="user-info d-flex align-items-center">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle me-2"
                            style="width: 32px; height: 32px; object-fit: cover; border: 2px solid var(--primary-red);">
                    @else
                        <i class="bi bi-person-circle fs-4 me-2"></i>
                    @endif
                    <span class="text-white fw-medium">{{ Auth::user()->name }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Đăng xuất</button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Delete Confirmation Helper -->
    <script>
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
                    confirmButton: 'btn btn-danger px-4 py-2 fw-semibold',
                    cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold'
                },
                buttonsStyling: false
            });
            return result.isConfirmed;
        }

        async function handleDeleteForm(button, message) {
            const confirmed = await confirmDelete(message);
            if (confirmed) {
                button.closest('form').submit();
            }
        }
    </script>
    @yield('scripts')
</body>

</html>