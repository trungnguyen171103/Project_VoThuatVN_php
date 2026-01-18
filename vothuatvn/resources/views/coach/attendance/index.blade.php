@extends('layouts.admin')

@section('title', 'Lịch điểm danh')
@section('page-title', 'Lịch Điểm Danh')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-month me-2"></i>Lịch dạy tháng {{ now()->format('m/Y') }}</h5>
                </div>
                <div class="card-body">
                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Thời gian</th>
                                        <th>Lớp học</th>
                                        <th>Câu lạc bộ</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $schedule)
                                        <tr>
                                            <td>
                                                <strong>{{ $schedule->date->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $schedule->date->locale('vi')->dayName }}</small>
                                            </td>
                                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                            <td><strong>{{ $schedule->classModel->name }}</strong></td>
                                            <td>{{ $schedule->classModel->club->name }}</td>
                                            <td>
                                                @if($schedule->date <= now())
                                                    <a href="{{ route('coach.attendance.show', $schedule->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-clipboard-check"></i> Điểm danh
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary">Chưa đến giờ</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Không có lịch dạy trong tháng này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection