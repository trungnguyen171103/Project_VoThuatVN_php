@extends('layouts.admin')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ Sơ Cá Nhân')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Profile Info Card -->
            <div class="card animate-scale-in mb-4"
                style="background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-header"
                    style="background: var(--color-bg-3); border-bottom: 1px solid var(--color-border);">
                    <h5 class="mb-0" style="color: var(--color-text-primary);"><i class="bi bi-person-circle me-2"></i>Thông
                        tin cá nhân</h5>
                </div>
                <div class="card-body" style="background: var(--color-bg-2);">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img id="avatar-preview"
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=dc2626&color=ffffff&size=128' }}"
                                    alt="Avatar" class="rounded-circle shadow"
                                    style="width: 128px; height: 128px; object-fit: cover; border: 3px solid var(--color-primary);">
                                <label for="avatar-input" class="position-absolute bottom-0 end-0 rounded-circle shadow"
                                    style="cursor: pointer; 
                                                       width: 40px; 
                                                       height: 40px; 
                                                       display: flex; 
                                                       align-items: center; 
                                                       justify-content: center;
                                                       background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                                                       border: 3px solid var(--color-bg-2);
                                                       transition: all 0.3s ease;
                                                       touch-action: manipulation;
                                                       -webkit-tap-highlight-color: rgba(220, 38, 38, 0.3);">
                                    <i class="bi bi-camera-fill" style="color: white; font-size: 1.1rem;"></i>
                                </label>
                                <input type="file" id="avatar-input" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <p class="small mt-2" style="color: var(--color-text-muted);">Nhấp vào biểu tượng camera để thay
                                đổi ảnh đại diện</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->username }}" disabled
                                style="background: var(--color-bg-3); color: var(--color-text-muted) !important; border: 1px solid var(--color-border);">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Họ và tên <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', Auth::user()->name) }}" required
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', Auth::user()->email) }}" required
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control"
                                value="{{ old('phone', Auth::user()->phone) }}"
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Cập nhật thông tin
                        </button>
                    </form>
                </div>
            </div>

            <!-- Password Change Card -->
            <div class="card animate-scale-in"
                style="animation-delay: 0.1s; background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-header"
                    style="background: var(--color-bg-3); border-bottom: 1px solid var(--color-border);">
                    <h5 class="mb-0" style="color: var(--color-text-primary);"><i class="bi bi-key me-2"></i>Đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body" style="background: var(--color-bg-2);">
                    <form action="{{ route('admin.profile.change-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Mật khẩu hiện tại <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Mật khẩu mới <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control" required
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="color: var(--color-text-secondary);">Xác nhận mật khẩu mới
                                <span class="text-danger">*</span></label>
                            <input type="password" name="new_password_confirmation" class="form-control" required
                                style="background: var(--color-bg-3); color: var(--color-text-primary) !important; border: 1px solid var(--color-border);">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check me-2"></i>Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('avatar-input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection