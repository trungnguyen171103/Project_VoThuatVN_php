@extends('layouts.admin')

@section('title', 'Quản lý Tài khoản')
@section('page-title', 'Quản Lý Tài Khoản Người Dùng')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up"
                style="background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-header"
                    style="background: var(--color-bg-3); border-bottom: 1px solid var(--color-border);">
                    <h5 class="mb-0" style="color: var(--color-text-primary);"><i class="bi bi-people me-2"></i>Danh sách
                        tài khoản</h5>
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
                                        <th style="color: var(--color-text-secondary) !important;">Vai trò</th>
                                        <th style="color: var(--color-text-secondary) !important;">Trạng thái</th>
                                        <th style="color: var(--color-text-secondary) !important;">Ngày tạo</th>
                                        <th style="color: var(--color-text-secondary) !important;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody style="background: var(--color-bg-2) !important;">
                                    @foreach($users as $index => $user)
                                        <tr style="background: var(--color-bg-2) !important;">
                                            <td style="color: var(--color-text-primary) !important;">{{ $index + 1 }}</td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                <code
                                                    style="color: var(--color-primary) !important; background: var(--color-bg-3); padding: 4px 8px; border-radius: 4px;">
                                                        {{ $user->username }}
                                                    </code>
                                            </td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                <strong>{{ $user->name }}</strong></td>
                                            <td style="color: var(--color-text-primary) !important;">{{ $user->email }}</td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge"
                                                        style="background: rgba(220, 38, 38, 0.2); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.3);">
                                                        <i class="bi bi-shield-fill-check"></i> ADMIN
                                                    </span>
                                                @elseif($user->role === 'coach')
                                                    <span class="badge"
                                                        style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3);">
                                                        <i class="bi bi-person-badge"></i> HLV
                                                    </span>
                                                @else
                                                    <span class="badge"
                                                        style="background: rgba(148, 163, 184, 0.2); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);">
                                                        <i class="bi bi-person"></i> USER
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <button class="btn btn-sm" disabled title="Tài khoản admin được bảo vệ"
                                                        style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); cursor: not-allowed;">
                                                        <i class="bi bi-shield-check"></i> Hoạt động
                                                    </button>
                                                @else
                                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        @if($user->status === 'active')
                                                            <button type="submit" class="btn btn-sm"
                                                                style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3);">
                                                                <i class="bi bi-check-circle"></i> Hoạt động
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm"
                                                                style="background: rgba(148, 163, 184, 0.2); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);">
                                                                <i class="bi bi-x-circle"></i> Đã khóa
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endif
                                            </td>
                                            <td style="color: var(--color-text-primary) !important;">
                                                {{ $user->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Không thể xóa admin">
                                                        <i class="bi bi-shield-lock"></i>
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3; color: var(--color-text-muted);"></i>
                            <p class="text-muted mt-3" style="color: var(--color-text-muted) !important;">Chưa có tài khoản
                                người dùng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection