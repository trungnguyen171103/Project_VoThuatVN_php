@extends('layouts.admin')

@section('title', 'Dashboard HLV')
@section('page-title', 'Dashboard Huấn Luyện Viên')

@section('content')
    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card animate-fade-in">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Tổng số lớp</p>
                            <h3 class="mb-0">{{ $totalClasses }}</h3>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Tổng số võ sinh</p>
                            <h3 class="mb-0">{{ $totalStudents }}</h3>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card animate-fade-in" style="animation-delay: 0.2s">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Võ sinh nợ phí</p>
                            <h3 class="mb-0 text-danger">{{ $studentsWithDebt->count() }}</h3>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Schedule --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Lịch dạy hôm nay</h5>
                </div>
                <div class="card-body">
                    @if($todaySchedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Lớp học</th>
                                        <th>Câu lạc bộ</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaySchedules as $schedule)
                                        <tr>
                                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                            <td><strong>{{ $schedule->classModel->name }}</strong></td>
                                            <td>{{ $schedule->classModel->club->name }}</td>
                                            <td>
                                                <a href="{{ route('coach.attendance.show', $schedule->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-clipboard-check"></i> Điểm danh
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-2">Không có lịch dạy hôm nay</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- My Classes --}}
        <div class="col-md-6 mb-4">
            <div class="card animate-fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i>Lớp học của tôi</h5>
                </div>
                <div class="card-body">
                    @if($classes->count() > 0)
                        @foreach($classes as $class)
                            <div class="class-item mb-3 p-3"
                                style="background: rgba(220, 38, 38, 0.05); border-left: 3px solid #dc2626; border-radius: 8px;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $class->name }}</h6>
                                        <small class="text-muted">{{ $class->club->name }}</small>
                                        <p class="mb-0 mt-2">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $class->students_count }}/{{ $class->max_students }} võ sinh
                                        </p>
                                    </div>
                                    <a href="{{ route('coach.classes.show', $class->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-2">Chưa có lớp học nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Frequently Absent Students --}}
        <div class="col-md-6 mb-4">
            <div class="card animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Võ sinh vắng nhiều</h5>
                </div>
                <div class="card-body">
                    @if($frequentlyAbsentStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Võ sinh</th>
                                        <th>Số buổi vắng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($frequentlyAbsentStudents as $record)
                                        <tr>
                                            <td>{{ $record->student->full_name }}</td>
                                            <td><span class="badge bg-warning">{{ $record->absent_count }} buổi</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.3; color: #22c55e;"></i>
                            <p class="text-muted mt-2">Không có võ sinh vắng nhiều</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection