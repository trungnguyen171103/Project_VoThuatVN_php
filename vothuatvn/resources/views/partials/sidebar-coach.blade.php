<aside class="sidebar">
    <div class="sidebar-header text-center">
        <a href="{{ route('coach.dashboard') }}" class="d-block mb-2">
            <img src="{{ asset('images/logoPNL.png') }}" alt="Logo" class="sidebar-logo animate-pulse-glow">
        </a>
        <small class="d-block text-muted">HLV PANEL</small>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('coach.dashboard') }}"
            class="menu-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('coach.students.index') }}"
            class="menu-item {{ request()->routeIs('coach.students.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Danh sách học viên
        </a>

        <div class="menu-item" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#classMenu">
            <i class="bi bi-book"></i> Lớp dạy <i class="bi bi-chevron-down float-end"></i>
        </div>
        <div class="collapse" id="classMenu">
            <a href="{{ route('coach.classes.index') }}" class="menu-item ps-5">
                <i class="bi bi-list"></i> Danh sách lớp
            </a>
        </div>

        <a href="{{ route('coach.schedule.index') }}"
            class="menu-item {{ request()->routeIs('coach.schedule.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Lịch dạy
        </a>

        <a href="{{ route('coach.attendance.index') }}"
            class="menu-item {{ request()->routeIs('coach.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-check-circle"></i> Điểm danh
        </a>

        <a href="{{ route('coach.debts.index') }}"
            class="menu-item {{ request()->routeIs('coach.debts.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Nợ học phí
        </a>

        <a href="{{ route('coach.profile.show') }}"
            class="menu-item {{ request()->routeIs('coach.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Hồ sơ cá nhân
        </a>
    </nav>
</aside>