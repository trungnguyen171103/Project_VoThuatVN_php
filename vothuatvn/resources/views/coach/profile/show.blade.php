@extends('layouts.admin')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ Sơ Cá Nhân')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body text-center pb-0">
                    <div class="mb-3">
                        <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=dc2626&color=ffffff&size=128' }}"
                            alt="Avatar" class="rounded-circle shadow"
                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--primary-red);">
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="120"><strong>Họ tên:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>SĐT:</strong></td>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Vai trò:</strong></td>
                            <td><span class="badge bg-primary">Huấn luyện viên</span></td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <a href="{{ route('coach.profile.edit') }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil"></i> Chỉnh sửa thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('coach.profile.change-password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection