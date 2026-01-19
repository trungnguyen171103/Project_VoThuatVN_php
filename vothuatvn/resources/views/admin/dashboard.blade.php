@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('styles')
    <style>
        /* Enhanced Warning Cards */
        .warning-card {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-radius: 14px;
            padding: 1.75rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .warning-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .warning-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.4);
        }

        .warning-card.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            box-shadow: 0 8px 16px rgba(249, 115, 22, 0.3);
        }

        .warning-card.orange:hover {
            box-shadow: 0 12px 24px rgba(249, 115, 22, 0.4);
        }

        .warning-card h5 {
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .warning-card p {
            font-size: 0.9375rem;
            opacity: 0.95;
            margin-bottom: 0;
        }

        .warning-card .btn {
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .warning-card .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        /* Enhanced Stat Cards - USE CSS VARIABLES */
        .stat-card {
            background: var(--color-bg-3);
            border-radius: 14px;
            padding: 2rem;
            border: 1px solid var(--color-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 32px rgba(220, 38, 38, 0.3);
            border-color: #dc2626;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.3;
            transform: scale(1.1);
        }

        .stat-value {
            font-size: 2.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin: 0.75rem 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-icon {
            font-size: 3.5rem;
            opacity: 0.15;
            transition: all 0.3s ease;
            color: #dc2626;
        }

        /* Enhanced Chart Containers - USE CSS VARIABLES */
        .chart-container {
            background: var(--color-bg-3);
            border-radius: 14px;
            padding: 2rem;
            border: 1px solid var(--color-border);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            border-color: var(--color-border-hover);
            box-shadow: var(--shadow-lg);
        }

        .chart-container h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--color-text-primary);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container h5 i {
            color: #dc2626;
        }

        /* Section Headers */
        .section-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--color-border);
        }

        .section-header h4 {
            font-weight: 700;
            color: var(--color-text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header h4 i {
            color: #dc2626;
            font-size: 1.5rem;
        }

        /* Welcome Section - USE CSS VARIABLES */
        .welcome-section {
            background: var(--color-bg-3);
            border-radius: 14px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-md);
        }

        .welcome-section h2 {
            font-weight: 800;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        /* Modal Enhancements - USE CSS VARIABLES */
        .modal-content {
            background: var(--color-bg-2);
            border: 1px solid var(--color-border);
        }

        .modal-header {
            border-bottom: 1px solid var(--color-border);
        }

        .modal-title {
            color: var(--color-text-primary);
            font-weight: 700;
        }

        .table-dark {
            background: var(--color-bg-3);
        }

        .table-dark thead th {
            background: var(--color-bg-2);
            border-color: var(--color-border);
            color: var(--color-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-dark tbody tr {
            border-color: var(--color-border);
            transition: all 0.2s ease;
        }

        .table-dark tbody tr:hover {
            background: rgba(220, 38, 38, 0.05);
        }

        .badge {
            padding: 0.375rem 0.75rem;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .stat-value {
                font-size: 2rem;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .warning-card {
                padding: 1.25rem;
            }

            .chart-container {
                padding: 1.25rem;
            }
        }

        /* Loading Animation for Charts */
        .chart-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            color: var(--color-text-muted);
        }

        .chart-loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }
    </style>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section animate-fade-in">
        <h2><i class="bi bi-hand-wave-fill"></i> Chào mừng, {{ Auth::user()->name }}!</h2>
        <p class="text-muted mb-0 mt-2">Tổng quan hệ thống quản lý võ thuật VoThuatVN</p>
    </div>

    <!-- Warning Cards -->
    @if($studentsWithDebt->count() > 0 || $frequentlyAbsentStudents->count() > 0 || $expiringClasses->count() > 0)
        <div class="section-header">
            <h4><i class="bi bi-exclamation-triangle-fill"></i> Cảnh báo quan trọng</h4>
        </div>

        <div class="row mb-4">
            @if($studentsWithDebt->count() > 0)
                <div class="col-md-4 mb-3">
                    <div class="warning-card animate-fade-in-up" style="animation-delay: 0.1s">
                        <h5><i class="bi bi-cash-stack"></i> Nợ học phí</h5>
                        <p class="mb-2">{{ $studentsWithDebt->count() }} võ sinh nợ học phí tháng này</p>
                        <a href="{{ route('admin.tuitions.debts') }}" class="btn btn-sm">
                            <i class="bi bi-eye"></i> Xem danh sách
                        </a>
                    </div>
                </div>
            @endif

            @if($frequentlyAbsentStudents->count() > 0)
                <div class="col-md-4 mb-3">
                    <div class="warning-card orange animate-fade-in-up" style="animation-delay: 0.2s">
                        <h5><i class="bi bi-person-x"></i> Vắng học nhiều</h5>
                        <p class="mb-2">{{ $frequentlyAbsentStudents->count() }} võ sinh vắng ≥ 2 buổi/tuần</p>
                        <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#absentModal">
                            <i class="bi bi-eye"></i> Xem danh sách
                        </button>
                    </div>
                </div>
            @endif

            @if($expiringClasses->count() > 0)
                <div class="col-md-4 mb-3">
                    <div class="warning-card orange animate-fade-in-up" style="animation-delay: 0.3s">
                        <h5><i class="bi bi-calendar-x"></i> Lớp sắp hết hạn</h5>
                        <p class="mb-2">{{ $expiringClasses->count() }} lớp sắp hết hạn (< 7 ngày)</p>
                                <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#expiringModal">
                                    <i class="bi bi-eye"></i> Xem danh sách
                                </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="section-header">
        <h4><i class="bi bi-bar-chart-fill"></i> Thống kê tổng quan</h4>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.1s"
                onclick="window.location='{{ route('admin.students.index') }}'">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-2">Tổng số võ sinh</p>
                        <div class="stat-value">{{ $totalStudents }}</div>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.2s"
                onclick="window.location='{{ route('admin.classes.index') }}'">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-2">Lớp đang hoạt động</p>
                        <div class="stat-value">{{ $totalActiveClasses }}</div>
                    </div>
                    <i class="bi bi-book stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.3s"
                onclick="window.location='{{ route('admin.users.index') }}'">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-2">Tổng số HLV</p>
                        <div class="stat-value">{{ $totalCoaches }}</div>
                    </div>
                    <i class="bi bi-person-badge stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.4s"
                onclick="window.location='{{ route('admin.tuitions.debts') }}'">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-2">Võ sinh nợ phí</p>
                        <div class="stat-value">{{ $totalStudentsWithDebt }}</div>
                    </div>
                    <i class="bi bi-exclamation-triangle stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="section-header">
        <h4><i class="bi bi-graph-up"></i> Biểu đồ phân tích</h4>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="chart-container animate-fade-in-up" style="animation-delay: 0.1s">
                <h5><i class="bi bi-bar-chart-line"></i> Võ sinh đăng ký theo CLB (tháng này)</h5>
                <canvas id="clubChart"></canvas>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="chart-container animate-fade-in-up" style="animation-delay: 0.2s">
                <h5><i class="bi bi-pie-chart"></i> Tỷ lệ đóng học phí</h5>
                <canvas id="tuitionChart"></canvas>
            </div>
        </div>

        <div class="col-12">
            <div class="chart-container animate-fade-in-up" style="animation-delay: 0.3s">
                <h5><i class="bi bi-graph-up-arrow"></i> Võ sinh mới theo tháng</h5>
                <canvas id="studentTrendChart"></canvas>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    <!-- Absent Students Modal -->
    <div class="modal fade" id="absentModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-x me-2"></i>Võ sinh vắng học nhiều</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tên võ sinh</th>
                                    <th>Số buổi vắng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($frequentlyAbsentStudents as $absent)
                                    <tr>
                                        <td>{{ $absent->student->full_name }}</td>
                                        <td><span class="badge bg-danger">{{ $absent->absent_count }} buổi</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Classes Modal -->
    <div class="modal fade" id="expiringModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar-x me-2"></i>Lớp sắp hết hạn</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mã lớp</th>
                                    <th>Tên lớp</th>
                                    <th>CLB</th>
                                    <th>Ngày kết thúc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiringClasses as $class)
                                    <tr>
                                        <td><code>{{ $class->class_code }}</code></td>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ $class->club->name }}</td>
                                        <td><span
                                                class="badge bg-warning text-dark">{{ $class->end_date->format('d/m/Y') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js Global Configuration
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // Club Chart (Bar)
        const clubCtx = document.getElementById('clubChart').getContext('2d');
        new Chart(clubCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($studentsByClub['labels']) !!},
                datasets: [{
                    label: 'Số võ sinh',
                    data: {!! json_encode($studentsByClub['values']) !!},
                    backgroundColor: 'rgba(220, 38, 38, 0.8)',
                    borderColor: 'rgba(220, 38, 38, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(239, 68, 68, 0.9)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(220, 38, 38, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Tuition Chart (Pie)
        const tuitionCtx = document.getElementById('tuitionChart').getContext('2d');
        new Chart(tuitionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($tuitionRatio['labels']) !!},
                datasets: [{
                    data: {!! json_encode($tuitionRatio['values']) !!},
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#cbd5e1',
                            padding: 15,
                            font: { size: 13 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(220, 38, 38, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Student Trend Chart (Line)
        const trendCtx = document.getElementById('studentTrendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($newStudentsByMonth['labels']) !!},
                datasets: [{
                    label: 'Võ sinh mới',
                    data: {!! json_encode($newStudentsByMonth['values']) !!},
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderColor: 'rgba(220, 38, 38, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgba(220, 38, 38, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#e2e8f0',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(220, 38, 38, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
@endsection