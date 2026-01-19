@extends('layouts.admin')

@section('title', 'Quản lý Tài khoản')
@section('page-title', 'Quản Lý Tài Khoản Người Dùng')

@section('content')
    {{-- Search Form --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in-up"
                style="background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-body" style="background: var(--color-bg-2);">
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm kiếm theo tên đăng nhập hoặc số điện thoại..."
                                        value="{{ request('search') }}"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search me-2"></i>Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if(request('search'))
                            <div class="mt-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- User List --}}
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up"
                style="animation-delay: 0.1s; background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-header"
                    style="background: var(--color-bg-3); border-bottom: 1px solid var(--color-border);">
                    <h5 class="mb-0" style="color: var(--color-text-primary);"><i class="bi bi-people me-2"></i>Danh sách
                        tài khoản
                        <span class="badge bg-primary ms-2">{{ $users->total() }} tài khoản</span>
                    </h5>
                </div>
                <div class="card-body" style="background: var(--color-bg-2);">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" style="background: var(--color-bg-2) !important;">
                                <thead style="background: var(--color-bg-3) !important;">
                                    <tr>
                                        <th style="color: var(--color-text-secondary) !important;">STT</th>
                                        <th style="color: var(--color-text-secondary) !important;">Tên đăng nhập</th>
                                        <th style="color: var(--color-text-secondary) !important;">Họ tên</th>
                                        <th style="color: var(--color-text-secondary) !important;">Email</th>
                                        <th style="color: var(--color-text-secondary) !important;">SĐT</th>
                                        <th style="color: var(--color-text-secondary) !important;">Vai trò</th>
                                        <th style="color: var(--color-text-secondary) !important;">Trạng thái</th>
                                        <th style="color: var(--color-text-secondary) !important;">Ngày tạo</th>
                                        <th style="color: var(--color-text-secondary) !important;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody style="background: var(--color-bg-2) !important;">
                                    @foreach($users as $index => $user)
                                        <tr style="background: var(--color-bg-2) !important;">
                                            <td style="color: var(--color-text-primary) !important;">
                                                {{ $users->firstItem() + $index }}</td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                <code
                                                    style="color: var(--color-primary) !important; background: var(--color-bg-3); padding: 4px 8px; border-radius: 4px;">
                                                                    {{ $user->username }}
                                                                </code>
                                            </td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td style="color: var(--color-text-primary) !important;">{{ $user->email }}</td>
                                            <td style="color: var(--color-text-primary) !important;">{{ $user->phone ?? '-' }}</td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge"
                                                        style="background: rgba(220, 38, 38, 0.2); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.3);">
                                                        <i class="bi bi-shield-fill-check"></i> ADMIN
                                                    </span>
                                                @else
                                                    <form action="{{ route('admin.users.update-role', $user->id) }}"
                                                        method="POST" class="d-inline role-change-form">
                                                        @csrf
                                                        @method('PUT') {{-- Use PUT for updating --}}
                                                        <select name="role" class="form-select form-select-sm role-select"
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-name="{{ $user->name }}"
                                                            style="width: auto; display: inline-block; background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>
                                                                USER</option>
                                                            <option value="coach"
                                                                {{ $user->role === 'coach' ? 'selected' : '' }}>HLV
                                                            </option>
                                                        </select>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <button class="btn btn-sm" disabled title="Tài khoản admin được bảo vệ"
                                                        style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); cursor: not-allowed;">
                                                        <i class="bi bi-shield-check"></i> Hoạt động
                                                    </button>
                                                @else
                                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        @if($user->status === 'active')
                                                            <button type="button" class="btn btn-sm"
                                                                style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3);"
                                                                onclick="handleDeleteForm(this, 'Bạn có chắc muốn <strong>khóa</strong> tài khoản <strong>{{ $user->username }}</strong>?')">
                                                                <i class="bi bi-check-circle"></i> Hoạt động
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm"
                                                                style="background: rgba(148, 163, 184, 0.2); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);"
                                                                onclick="handleDeleteForm(this, 'Bạn có chắc muốn <strong>mở khóa</strong> tài khoản <strong>{{ $user->username }}</strong>?')">
                                                                <i class="bi bi-x-circle"></i> Đã khóa
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endif
                                            </td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    {{-- Delete User --}}
                                                    @if($user->role === 'admin')
                                                        <button class="btn btn-sm btn-outline-secondary" disabled
                                                            title="Không thể xóa admin">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="handleDeleteForm(this, 'Bạn có chắc muốn xóa tài khoản <strong>{{ $user->username }}</strong>?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3; color: var(--color-text-muted);"></i>
                            <p class="text-muted mt-3" style="color: var(--color-text-muted) !important;">
                                @if(request('search'))
                                    Không tìm thấy tài khoản nào phù hợp
                                @else
                                    Chưa có tài khoản người dùng nào
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle role change
            document.querySelectorAll('.role-select').forEach(select => {
                select.addEventListener('change', async function() {
                    const userId = this.dataset.userId;
                    const userName = this.dataset.userName;
                    const newRole = this.value;
                    const oldRole = this.options[this.selectedIndex === 0 ? 1 : 0].value; // Get the other option's value

                    let message = '';
                    if (newRole === 'coach') {
                        message = `Bạn có chắc muốn phân quyền HLV cho <strong>${userName}</strong>?`;
                    } else {
                        message = `Bạn có chắc muốn xóa quyền HLV của <strong>${userName}</strong>?`;
                    }

                    const confirmed = await confirmDelete(message, 'Xác nhận thay đổi vai trò?');
                    if (confirmed) {
                        this.closest('form').submit();
                    } else {
                        // Revert selection
                        this.value = oldRole;
                    }
                });
            });
        });
    </script>
@endpush