@extends('layouts.admin')

@section('title', 'Lịch dạy')
@section('page-title', 'Lịch Dạy')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Lịch dạy của tôi</h5>
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
                                        <th>Trạng thái</th>
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
                                                @if($schedule->date < now()->startOfDay())
                                                    <span class="badge bg-secondary">Đã qua</span>
                                                @elseif($schedule->date->isToday())
                                                    <span class="badge bg-success">Hôm nay</span>
                                                @else
                                                    <span class="badge bg-primary">Sắp tới</span>
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
                            <p class="text-muted mt-3">Không có lịch dạy</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection