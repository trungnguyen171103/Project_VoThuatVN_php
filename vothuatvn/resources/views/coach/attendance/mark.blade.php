@extends('layouts.admin')

@section('title', 'Điểm danh')
@section('page-title', 'Điểm Danh - ' . $schedule->classModel->name)

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('coach.attendance.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin buổi học</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Lớp học:</strong></p>
                            <p>{{ $schedule->classModel->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Câu lạc bộ:</strong></p>
                            <p>{{ $schedule->classModel->club->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Ngày:</strong></p>
                            <p>{{ $schedule->date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Thời gian:</strong></p>
                            <p>{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Điểm danh võ sinh</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('coach.attendance.store', $schedule->id) }}" method="POST">
                        @csrf

                        @if($schedule->classModel->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">STT</th>
                                            <th>Họ tên</th>
                                            <th width="150" class="text-center">Có mặt</th>
                                            <th width="150" class="text-center">Vắng</th>
                                            <th width="150" class="text-center">Vắng có phép</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedule->classModel->students as $index => $student)
                                            @php
                                                $currentStatus = $existingAttendance[$student->id] ?? 'present';
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $student->full_name }}</strong></td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                            name="attendance[{{ $student->id }}]" 
                                                            value="present" 
                                                            id="present_{{ $student->id }}"
                                                            {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="present_{{ $student->id }}">
                                                            <i class="bi bi-check-circle text-success"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                            name="attendance[{{ $student->id }}]" 
                                                            value="absent" 
                                                            id="absent_{{ $student->id }}"
                                                            {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="absent_{{ $student->id }}">
                                                            <i class="bi bi-x-circle text-danger"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                            name="attendance[{{ $student->id }}]" 
                                                            value="excused" 
                                                            id="excused_{{ $student->id }}"
                                                            {{ $currentStatus === 'excused' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="excused_{{ $student->id }}">
                                                            <i class="bi bi-exclamation-circle text-warning"></i>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú về buổi học..."></textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Lưu điểm danh
                                </button>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-2">Lớp học chưa có võ sinh nào</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
