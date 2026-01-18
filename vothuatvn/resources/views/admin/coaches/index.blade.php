@extends('layouts.admin')

@section('title', 'Quản lý HLV')
@section('page-title', 'Quản lý Huấn Luyện Viên')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Tìm kiếm tài khoản để phân quyền HLV</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coaches.search') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" name="username" class="form-control"
                                        placeholder="Nhập tên đăng nhập..." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Danh sách HLV</h5>
                    <span class="badge bg-primary">{{ $coaches->total() }} HLV</span>
                </div>
                <div class="card-body">
                    @if($coaches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Họ và tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coaches as $index => $coach)
                                        <tr>
                                            <td>{{ $coaches->firstItem() + $index }}</td>
                                            <td><code>{{ $coach->user->username }}</code></td>
                                            <td>{{ $coach->user->name }}</td>
                                            <td>{{ $coach->user->email }}</td>
                                            <td>{{ $coach->user->phone ?? '-' }}</td>
                                            <td>{{ $coach->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.coaches.destroy', $coach->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="handleDeleteForm(this, 'Bạn có chắc muốn xóa quyền HLV của <strong>{{ $coach->user->name }}</strong>?')">
                                                        <i class="bi bi-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $coaches->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Chưa có HLV nào được phân quyền</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection