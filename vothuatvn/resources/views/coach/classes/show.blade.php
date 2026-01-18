@extends('layouts.admin')

@section('title', 'Chi tiết lớp học')
@section('page-title', $class->name)

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('coach.classes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin lớp học</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Mã lớp:</strong></td>
                            <td><code>{{ $class->class_code }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Câu lạc bộ:</strong></td>
                            <td>{{ $class->club->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Sĩ số:</strong></td>
                            <td>{{ $class->students->count() }}/{{ $class->max_students }} võ sinh</td>
                        </tr>
                        <tr>
                            <td><strong>Thời gian:</strong></td>
                            <td>
                                {{ $class->start_date->format('d/m/Y') }}
                                @if($class->end_date)
                                    - {{ $class->end_date->format('d/m/Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                @if($class->status === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Kết thúc</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Mô tả</h5>
                </div>
                <div class="card-body">
                    <p>{{ $class->description ?? 'Chưa có mô tả' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Danh sách võ sinh ({{ $class->students->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($class->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Năm sinh</th>
                                        <th>SĐT</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class->students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $student->full_name }}</strong></td>
                                            <td>{{ $student->birth_year }}</td>
                                            <td>{{ $student->phone ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('coach.students.show', $student->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-2">Chưa có võ sinh nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection