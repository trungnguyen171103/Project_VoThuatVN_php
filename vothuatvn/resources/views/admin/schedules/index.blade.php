@extends('layouts.admin')

@section('title', 'Lịch học')
@section('page-title', 'Quản Lý Lịch Học')

@section('content')
    <div class="schedule-container fade-in-up">
        <!-- Header & Controls -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <a href="{{ route('admin.schedules.create') }}"
                class="btn btn-primary d-flex align-items-center shadow-sm btn-hover-effect">
                <i class="bi bi-plus-lg me-2"></i>
                <span class="fw-medium">Tạo lịch học mới</span>
            </a>
        </div>

        <!-- Class Cards Grid -->
        <div class="row g-4">
            @forelse($classes as $class)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm class-schedule-card">
                        <div class="card-body d-flex flex-column p-4">
                            <!-- Class Name & Code -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title fw-bold text-dark mb-1">{{ $class['name'] }}</h5>
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary">{{ $class['class_code'] }}</span>
                                    </div>
                                    @if($class['status'] == 'active')
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Club -->
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-building me-2 text-info"></i>
                                <span class="small text-muted">{{ $class['club_name'] }}</span>
                            </div>

                            <!-- Course Duration -->
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-range me-2 text-success"></i>
                                <span class="small">
                                    {{ $class['start_date']->format('d/m/Y') }} -
                                    {{ $class['end_date'] ? $class['end_date']->format('d/m/Y') : 'Không xác định' }}
                                </span>
                            </div>

                            <!-- Schedule Time -->
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2 text-warning"></i>
                                <span class="fw-bold">{{ $class['schedule_time'] }}</span>
                            </div>

                            <!-- Coach -->
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-badge me-2 text-secondary"></i>
                                <span class="small">{{ $class['coach_name'] }}</span>
                            </div>

                            <!-- Schedule Count Badge -->
                            <div class="mb-3">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    {{ $class['schedule_count'] }} buổi học
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-auto">
                                <button type="button"
                                    class="btn btn-primary btn-sm flex-grow-1 d-flex align-items-center justify-content-center"
                                    onclick="openScheduleModal({{ $class['id'] }}, '{{ $currentWeek }}')">
                                    <i class="bi bi-eye-fill me-1"></i> Xem lịch
                                </button>

                                <form action="{{ route('admin.schedules.destroy-class', $class['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm px-3" title="Xoá lịch"
                                        onclick="handleDeleteForm(this, 'Bạn có chắc chắn muốn xoá tất cả lịch học của lớp <strong>{{ $class['name'] }}</strong>?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="bg-secondary bg-opacity-25 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-calendar-x text-secondary display-4"></i>
                        </div>
                        <h5 class="text-secondary fw-normal">Chưa có lịch học nào được tạo</h5>
                        <p class="text-muted">Hãy tạo lịch học mới để bắt đầu</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('modals')
        <!-- Schedule Detail Modal - Redesigned -->
        <div class="modal fade" id="scheduleDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-xl" style="border-radius: 20px; overflow: hidden;">

                    <!-- Modal Header - Elegant Design -->
                    <div class="schedule-modal-header">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                        <div class="container-fluid">
                            <div class="row align-items-center">
                                <!-- Class Info -->
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="modal-icon">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h4 class="modal-class-name mb-1" id="modalClassName">--</h4>
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <span class="modal-badge" id="modalClassCode">--</span>
                                                <span class="modal-info-item">
                                                    <i class="bi bi-building me-1"></i>
                                                    <span id="modalClub">--</span>
                                                </span>
                                                <span class="modal-info-item">
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    <span id="modalCoach">--</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Week Navigation -->
                                <div class="col-12">
                                    <div class="week-nav-box">
                                        <div class="text-center mb-2">
                                            <small class="text-white-50 text-uppercase fw-bold"
                                                style="font-size: 0.7rem; letter-spacing: 1px;">Lịch học tuần</small>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <button type="button" class="btn-week-nav" id="prevWeekBtn" title="Tuần trước">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <button type="button" class="btn-week-current" id="currentWeekBtn" title="Tuần này">
                                                <i class="bi bi-calendar-day me-1"></i>
                                                Tuần này
                                            </button>
                                            <div class="week-range-display flex-grow-1" id="modalWeekRange">
                                                <i class="bi bi-calendar3 me-2"></i>
                                                Tuần hiện tại
                                            </div>
                                            <button type="button" class="btn-week-nav" id="nextWeekBtn" title="Tuần sau">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body - Weekly Schedule Grid -->
                    <div class="modal-body p-0">
                        <div id="scheduleListContainer" class="schedule-content-area">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Đang tải...</span>
                                </div>
                                <p class="text-muted mt-2 mb-0">Đang tải lịch học...</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            let currentClassId = null;
            let currentWeek = '{{ $currentWeek }}';

            function openScheduleModal(classId, week) {
                currentClassId = classId;
                currentWeek = week;

                // Show modal
                const modalEl = document.getElementById('scheduleDetailModal');
                if (modalEl) {
                    var myModal = new bootstrap.Modal(modalEl);
                    myModal.show();

                    // Load data
                    loadClassWeekSchedules(classId, week);
                }
            }

            function loadClassWeekSchedules(classId, week) {
                const container = document.getElementById('scheduleListContainer');
                if (!container) return;

                // Show loading
                container.innerHTML = `
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Đang tải...</span>
                                    </div>
                                </div>
                            `;

                // Fetch data
                fetch(`{{ route('admin.schedules.class-week') }}?class_id=${classId}&week=${week}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update class info
                        const nameEl = document.getElementById('modalClassName');
                        const codeEl = document.getElementById('modalClassCode');
                        const clubEl = document.getElementById('modalClub');
                        const coachEl = document.getElementById('modalCoach');
                        const rangeEl = document.getElementById('modalWeekRange');

                        if (nameEl) nameEl.textContent = data.class.name;
                        if (codeEl) codeEl.textContent = data.class.class_code;
                        if (clubEl) clubEl.textContent = data.class.club_name;
                        if (coachEl) coachEl.textContent = data.class.coach_name;
                        if (rangeEl) rangeEl.innerHTML = `<i class="bi bi-calendar3 me-2"></i>${data.week_label}`;

                        // Update current week for navigation
                        currentWeek = week;

                        // Render schedules
                        if (data.schedules.length === 0) {
                            container.innerHTML = `
                                                <div class="text-center py-4">
                                                    <i class="bi bi-calendar-x text-muted display-4"></i>
                                                    <p class="text-muted mt-2">Không có buổi học nào trong tuần này</p>
                                                </div>
                                            `;
                        } else {
                            // Create weekly calendar grid
                            const daysOfWeek = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'];
                            const timeSlots = [];

                            // Generate time slots from 16:00 to 19:00 (Tiết 1-3)
                            for (let hour = 16; hour <= 18; hour++) {
                                const slotNumber = hour - 15;
                                timeSlots.push({
                                    label: `Tiết ${slotNumber}`,
                                    timeRange: `${hour.toString().padStart(2, '0')}:00 - ${(hour + 1).toString().padStart(2, '0')}:00`,
                                    start: `${hour.toString().padStart(2, '0')}:00`,
                                    end: `${(hour + 1).toString().padStart(2, '0')}:00`
                                });
                            }

                            // Build calendar grid
                            let calendarHtml = `
                                                <div class="weekly-calendar">
                                                    <table class="table table-bordered calendar-grid">
                                                        <thead>
                                                            <tr>
                                                                <th class="time-column"></th>
                                                                ${daysOfWeek.map(day => `<th class="day-header">${day}</th>`).join('')}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                            `;

                            // Create rows for each time slot
                            timeSlots.forEach(slot => {
                                calendarHtml += `<tr>`;
                                calendarHtml += `<td class="time-slot">
                                                    <div class="time-label">${slot.label}</div>
                                                    <div class="time-range">${slot.timeRange}</div>
                                                </td>`;

                                // Create cells for each day
                                for (let dayIdx = 0; dayIdx < 7; dayIdx++) {
                                    // Find schedules for this day and time slot
                                    const matchingSchedules = data.schedules.filter(schedule => {
                                        const scheduleDayOfWeek = schedule.day_of_week;
                                        const scheduleStart = schedule.start_time;

                                        // Convert day_of_week (0=Sun, 1=Mon...) to our index (0=Mon, 6=Sun)
                                        const adjustedDay = scheduleDayOfWeek === 0 ? 6 : scheduleDayOfWeek - 1;

                                        return adjustedDay === dayIdx && scheduleStart >= slot.start && scheduleStart < slot.end;
                                    });

                                    if (matchingSchedules.length > 0) {
                                        const schedule = matchingSchedules[0];
                                        calendarHtml += `
                                                            <td class="calendar-cell has-schedule">
                                                                <div class="schedule-item">
                                                                    <div class="schedule-class-name">${data.class.name}</div>
                                                                    <div class="schedule-class-code">(${data.class.class_code})</div>
                                                                    <div class="schedule-time">${schedule.start_time} - ${schedule.end_time}</div>
                                                                    <div class="schedule-date">${schedule.date}</div>
                                                                    <div class="schedule-coach">GV: ${data.class.coach_name}</div>
                                                                </div>
                                                            </td>
                                                        `;
                                    } else {
                                        calendarHtml += `<td class="calendar-cell"></td>`;
                                    }
                                }

                                calendarHtml += `</tr>`;
                            });

                            calendarHtml += `
                                                        </tbody>
                                                    </table>
                                                </div>
                                            `;

                            container.innerHTML = calendarHtml;
                        }

                        // Store prev/next week for navigation
                        const prevBtn = document.getElementById('prevWeekBtn');
                        const nextBtn = document.getElementById('nextWeekBtn');
                        if (prevBtn) prevBtn.dataset.week = data.prev_week;
                        if (nextBtn) nextBtn.dataset.week = data.next_week;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        container.innerHTML = `
                                            <div class="alert alert-danger">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.
                                            </div>
                                        `;
                    });
            }

            // Initialize event listeners after DOM is fully loaded and elements are available
            document.addEventListener('DOMContentLoaded', function () {
                const prevBtn = document.getElementById('prevWeekBtn');
                const nextBtn = document.getElementById('nextWeekBtn');
                const currentBtn = document.getElementById('currentWeekBtn');

                if (prevBtn) {
                    prevBtn.addEventListener('click', function () {
                        const prevWeek = this.dataset.week;
                        if (currentClassId && prevWeek) {
                            loadClassWeekSchedules(currentClassId, prevWeek);
                        }
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function () {
                        const nextWeek = this.dataset.week;
                        if (currentClassId && nextWeek) {
                            loadClassWeekSchedules(currentClassId, nextWeek);
                        }
                    });
                }

                if (currentBtn) {
                    currentBtn.addEventListener('click', function () {
                        const thisWeek = '{{ $currentWeek }}';
                        if (currentClassId) {
                            loadClassWeekSchedules(currentClassId, thisWeek);
                        }
                    });
                }
            });
        </script>
    @endpush

    <style>
        /* Class Card Styling - USE CSS VARIABLES */
        .class-schedule-card {
            background: var(--color-bg-3);
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid var(--color-border);
        }

        .class-schedule-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl) !important;
            border-color: var(--color-border-hover);
        }

        .class-schedule-card .card-title {
            font-size: 1.1rem;
            line-height: 1.3;
            color: var(--color-text-primary) !important;
        }

        .class-schedule-card .card-body {
            color: var(--color-text-secondary);
        }

        .class-schedule-card .text-dark {
            color: var(--color-text-primary) !important;
        }

        .class-schedule-card .text-muted {
            color: var(--color-text-muted) !important;
        }

        .class-schedule-card .small {
            color: var(--color-text-secondary);
        }

        .class-schedule-card .badge.bg-primary {
            background-color: rgba(13, 110, 253, 0.2) !important;
            color: #6ea8fe !important;
            border: 1px solid rgba(13, 110, 253, 0.3);
        }

        .class-schedule-card .badge.bg-light {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--color-text-primary) !important;
            border-color: var(--color-border) !important;
        }

        /* Status Badge - Eye-catching Design */
        .status-badge {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
        }

        .status-badge:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-active:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .status-inactive {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .status-inactive:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        }

        /* Modal Styling - Redesigned */
        #scheduleDetailModal .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        /* Modal Header - Gradient Background */
        .schedule-modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 1.5rem;
            position: relative;
            color: white;
        }

        .schedule-modal-header .btn-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            opacity: 0.8;
            z-index: 10;
        }

        .schedule-modal-header .btn-close:hover {
            opacity: 1;
        }

        /* Modal Icon */
        .modal-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Modal Class Name */
        .modal-class-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Modal Badge */
        .modal-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Modal Info Items */
        .modal-info-item {
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.95);
        }

        .modal-info-item i {
            opacity: 0.8;
        }

        /* Week Navigation Box */
        .week-nav-box {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .week-range-display {
            background: rgba(255, 255, 255, 0.25);
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            text-align: center;
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-week-nav {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            flex-shrink: 0;
        }

        .btn-week-nav:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-week-current {
            padding: 0.6rem 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-week-current:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Schedule Content Area */
        .schedule-content-area {
            min-height: 400px;
            background: #f8f9fa;
        }

        /* Table Styling */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Weekly Calendar Grid Styling - Enhanced */
        .weekly-calendar {
            overflow-x: auto;
            padding: 1.5rem;
        }

        .calendar-grid {
            margin: 0;
            background: white;
            min-width: 900px;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }

        .calendar-grid thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 1rem 0.5rem;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-grid thead th:first-child {
            border-top-left-radius: 12px;
        }

        .calendar-grid thead th:last-child {
            border-top-right-radius: 12px;
        }

        .calendar-grid .time-column {
            width: 90px;
            background: #f8f9fa;
        }

        .calendar-grid .day-header {
            min-width: 130px;
        }

        .calendar-grid .time-slot {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 600;
            text-align: center;
            font-size: 0.85rem;
            color: #495057;
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        .calendar-grid tbody tr:last-child .time-slot {
            border-bottom: none;
        }

        .calendar-grid .time-label {
            font-weight: 700;
            font-size: 0.9rem;
            color: #2d3142;
            margin-bottom: 0.25rem;
        }

        .calendar-grid .time-range {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }

        .calendar-grid .calendar-cell {
            padding: 0.5rem;
            vertical-align: middle;
            height: 140px;
            border-right: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .calendar-grid tbody tr:last-child .calendar-cell {
            border-bottom: none;
        }

        .calendar-grid .calendar-cell:last-child {
            border-right: none;
        }

        .calendar-grid .calendar-cell:hover {
            background: #f8f9fa;
        }

        .calendar-grid .calendar-cell.has-schedule {
            background: linear-gradient(135deg, #e7f3ff 0%, #f0f8ff 100%);
            padding: 0.4rem;
        }

        .calendar-grid .schedule-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 0.85rem;
            border-radius: 10px;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .calendar-grid .schedule-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5568d3 0%, #6941a1 100%);
        }

        .calendar-grid .schedule-time {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.3rem;
            letter-spacing: 0.3px;
        }

        .calendar-grid .schedule-class-name {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
            line-height: 1.2;
        }

        .calendar-grid .schedule-class-code {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .calendar-grid .schedule-date {
            font-size: 0.8rem;
            opacity: 0.95;
            margin-bottom: 0.3rem;
            font-weight: 500;
        }

        .calendar-grid .schedule-coach {
            font-size: 0.75rem;
            opacity: 0.9;
            font-style: italic;
            font-weight: 500;
        }

        .calendar-grid .schedule-item small {
            font-size: 0.75rem;
            opacity: 0.95;
            font-weight: 500;
        }

        /* Empty state styling */
        .calendar-grid+.text-center {
            padding: 3rem 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .weekly-calendar {
                padding: 1rem;
            }

            .calendar-grid {
                font-size: 0.85rem;
                min-width: 800px;
            }

            .calendar-grid .day-header {
                min-width: 110px;
                font-size: 0.75rem;
                padding: 0.85rem 0.4rem;
            }

            .calendar-grid .time-slot {
                width: 75px;
                font-size: 0.75rem;
                padding: 0.85rem 0.4rem;
            }

            .calendar-grid .calendar-cell {
                height: 65px;
                padding: 0.4rem;
            }

            .calendar-grid .schedule-item {
                padding: 0.6rem 0.7rem;
                font-size: 0.8rem;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .class-schedule-card .card-body {
                padding: 1.25rem !important;
            }
        }

        /* ============================================
                   MODAL Z-INDEX FIX - ENSURE CLICKABLE
                   ============================================ */
        #scheduleDetailModal {
            z-index: 9999 !important;
        }

        #scheduleDetailModal .modal-dialog {
            z-index: 10000 !important;
            position: relative;
        }

        #scheduleDetailModal .modal-content {
            z-index: 10001 !important;
            position: relative;
        }

        /* Ensure all buttons/inputs in modal are clickable */
        #scheduleDetailModal button,
        #scheduleDetailModal input,
        #scheduleDetailModal a,
        #scheduleDetailModal .btn {
            position: relative;
            z-index: 10002 !important;
            pointer-events: auto !important;
        }
    </style>
@endsection