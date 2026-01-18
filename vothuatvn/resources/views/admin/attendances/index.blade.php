@extends('layouts.admin')

@section('title', 'Điểm danh')
@section('page-title', 'Điểm Danh Võ Sinh')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in">
                <div class="card-body">
                    <form action="{{ route('admin.attendances.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Chọn lớp học</label>
                                    <select name="class_id" class="form-control" required>
                                        <option value="">-- Chọn lớp --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} ({{ $class->class_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Ngày điểm danh</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ request('date', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Xem danh sách
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($students) && $students->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card animate-fade-in-up">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-check2-square me-2"></i>
                            Điểm danh lớp {{ $selectedClass->name }} -
                            {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.attendances.mark') }}" method="POST">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                            <input type="hidden" name="date" value="{{ request('date') }}">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ tên</th>
                                            <th>Năm sinh</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $student->full_name }}</strong></td>
                                                <td>{{ $student->birth_year }}</td>
                                                <td>
                                                    <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                    <select name="attendances[{{ $index }}][status]"
                                                        class="form-select form-select-sm status-select-attendance" style="width: 160px;">
                                                        <option value="present" {{ ($attendances[$student->id] ?? '') === 'present' ? 'selected' : '' }}>
                                                            ✓ Có mặt
                                                        </option>
                                                        <option value="absent" {{ ($attendances[$student->id] ?? '') === 'absent' ? 'selected' : '' }}>
                                                            ✗ Vắng
                                                        </option>
                                                        <option value="excused" {{ ($attendances[$student->id] ?? '') === 'excused' || ($attendances[$student->id] ?? '') === 'leave' ? 'selected' : '' }}>
                                                            P Nghỉ phép
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Lưu điểm danh
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request('class_id'))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Lớp này chưa có võ sinh nào</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection