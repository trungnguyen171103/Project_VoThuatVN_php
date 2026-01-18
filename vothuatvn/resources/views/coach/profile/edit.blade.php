@extends('layouts.admin')

@section('title', 'Chỉnh sửa hồ sơ')
@section('page-title', 'Chỉnh Sửa Hồ Sơ')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('coach.profile.show') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Chỉnh sửa thông tin</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('coach.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img id="avatar-preview"
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=dc2626&color=ffffff&size=128' }}"
                                    alt="Avatar" class="rounded-circle shadow"
                                    style="width: 128px; height: 128px; object-fit: cover; border: 3px solid var(--color-primary);">
                                <label for="avatar-input"
                                    class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm"
                                    style="cursor: pointer; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-camera"></i>
                                </label>
                                <input type="file" id="avatar-input" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <p class="small text-muted mt-2">Nhấp vào biểu tượng camera để thay đổi ảnh đại diện</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('coach.profile.show') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
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