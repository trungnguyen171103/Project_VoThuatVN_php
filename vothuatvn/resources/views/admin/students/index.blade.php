@extends('layouts.admin')

@section('title', 'Quản lý Võ sinh')
@section('page-title', 'Quản lý Võ Sinh')

@section('content')
    <!-- Search & Add -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in">
                <div class="card-body">
                    <form action="{{ route('admin.students.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">Tìm kiếm</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tên hoặc số điện thoại..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-search me-2"></i>Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.students.create') }}" class="btn btn-success flex-fill">
                                        <i class="bi bi-plus-circle me-2"></i>Thêm võ sinh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Danh sách Võ sinh</h5>
                    <span class="badge bg-primary">{{ $students->total() }} võ sinh</span>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ và tên</th>
                                        <th>Năm sinh</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày đăng ký</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $students->firstItem() + $index }}</td>
                                            <td><strong>{{ $student->full_name }}</strong></td>
                                            <td>{{ $student->birth_year }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ Str::limit($student->address, 30) }}</td>
                                            <td>{{ $student->registration_date ? \Carbon\Carbon::parse($student->registration_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                @if($student->status === 'active')
                                                    <span class="badge bg-success">Đang học</span>
                                                @else
                                                    <span class="badge bg-secondary">Nghỉ học</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.students.edit', $student->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="handleDeleteForm(this, 'Bạn có chắc muốn xóa võ sinh <strong>{{ $student->full_name }}</strong>?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Chưa có võ sinh nào</p>
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Thêm võ sinh đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection