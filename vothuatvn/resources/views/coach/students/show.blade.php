@extends('layouts.admin')

@section('title', 'Hồ sơ võ sinh')
@section('page-title', $student->full_name)

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('coach.students.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="120"><strong>Họ tên:</strong></td>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Năm sinh:</strong></td>
                            <td>{{ $student->birth_year }}</td>
                        </tr>
                        <tr>
                            <td><strong>SĐT:</strong></td>
                            <td>{{ $student->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Địa chỉ:</strong></td>
                            <td>{{ $student->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Thống kê điểm danh</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h3 class="text-primary">{{ $totalSessions }}</h3>
                            <p class="text-muted">Tổng buổi học</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">{{ $presentCount }}</h3>
                            <p class="text-muted">Có mặt</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-danger">{{ $attendanceRate }}%</h3>
                            <p class="text-muted">Tỷ lệ tham gia</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i>Lớp học</h5>
                </div>
                <div class="card-body">
                    @foreach($student->classes as $class)
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $class->name }}</span>
                            <small class="text-muted ms-2">{{ $class->club->name }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử điểm danh</h5>
                </div>
                <div class="card-body">
                    @if($attendanceHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Lớp học</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendanceHistory as $record)
                                        <tr>
                                            <td>{{ $record->date->format('d/m/Y') }}</td>
                                            <td>{{ $record->classModel->name }}</td>
                                            <td>
                                                @if($record->status === 'present')
                                                    <span class="badge bg-success">Có mặt</span>
                                                @elseif($record->status === 'absent')
                                                    <span class="badge bg-danger">Vắng</span>
                                                @else
                                                    <span class="badge bg-warning">Vắng có phép</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $attendanceHistory->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-2">Chưa có lịch sử điểm danh</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection