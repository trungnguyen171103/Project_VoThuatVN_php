<aside class="sidebar" id="admin-sidebar">
    <div class="sidebar-header text-center">
        <a href="{{ route('admin.dashboard') }}" class="d-block mb-2">
            <img src="{{ asset('images/logoPNL.png') }}" alt="Logo" class="sidebar-logo animate-pulse-glow">
        </a>
        <small class="d-block text-muted">HỆ THỐNG QUẢN LÝ</small>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.coaches.index') }}"
            class="menu-item {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span>Quản lý HLV</span>
        </a>

        <a href="{{ route('admin.clubs.index') }}"
            class="menu-item {{ request()->routeIs('admin.clubs.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Quản lý CLB</span>
        </a>

        <a href="{{ route('admin.classes.index') }}"
            class="menu-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i>
            <span>Quản lý Lớp học</span>
        </a>

        <a href="{{ route('admin.students.index') }}"
            class="menu-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Quản lý Võ sinh</span>
        </a>

        <a href="{{ route('admin.schedules.index') }}"
            class="menu-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Lịch học</span>
        </a>

        <a href="{{ route('admin.attendances.index') }}"
            class="menu-item {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
            <i class="bi bi-check-circle"></i>
            <span>Điểm danh</span>
        </a>

        <div class="menu-item" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#tuitionMenu"
            aria-expanded="{{ request()->routeIs('admin.tuitions.*') ? 'true' : 'false' }}">
            <i class="bi bi-cash-coin"></i>
            <span>Học phí</span>
            <i class="bi bi-chevron-down ms-auto" style="width: auto; margin-right: 0;"></i>
        </div>
        <div class="collapse {{ request()->routeIs('admin.tuitions.*') ? 'show' : '' }}" id="tuitionMenu">
            <a href="{{ route('admin.tuitions.create') }}"
                class="menu-item ps-5 {{ request()->routeIs('admin.tuitions.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Tạo học phí</span>
            </a>
            <a href="{{ route('admin.tuitions.debts') }}"
                class="menu-item ps-5 {{ request()->routeIs('admin.tuitions.debts') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Danh sách nợ</span>
            </a>
        </div>

        <a href="{{ route('admin.profile.edit') }}"
            class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Hồ sơ cá nhân</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>Quản lý Tài khoản</span>
        </a>
    </nav>
</aside>