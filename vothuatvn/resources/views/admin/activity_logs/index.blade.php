@extends('layouts.admin')

@section('title', 'Nhật ký hoạt động')
@section('page-title', 'Nhật Ký Hoạt Động')

@section('content')
    {{-- Filter Form --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in-up"
                style="background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-body" style="background: var(--color-bg-2);">
                    <form action="{{ route('admin.activity-logs.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" style="color: var(--color-text-secondary);">Người dùng</label>
                                    <select name="user_id" class="form-select"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                        <option value="">Tất cả</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" style="color: var(--color-text-secondary);">Hành động</label>
                                    <select name="action" class="form-select"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                        <option value="">Tất cả</option>
                                        @foreach($actions as $action)
                                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                {{ \App\Services\ActivityLogger::getActionName($action) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" style="color: var(--color-text-secondary);">Từ ngày</label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="{{ request('from_date') }}"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" style="color: var(--color-text-secondary);">Đến ngày</label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="{{ request('to_date') }}"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm kiếm mô tả..."
                                        value="{{ request('search') }}"
                                        style="background: var(--color-bg-3); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if(request()->hasAny(['user_id', 'action', 'from_date', 'to_date', 'search']))
                            <div class="mt-2">
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Logs List --}}
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up"
                style="animation-delay: 0.1s; background: var(--color-bg-2); border: 1px solid var(--color-border);">
                <div class="card-header"
                    style="background: var(--color-bg-3); border-bottom: 1px solid var(--color-border);">
                    <h5 class="mb-0" style="color: var(--color-text-primary);">
                        <i class="bi bi-clock-history me-2"></i>Danh sách hoạt động
                        <span class="badge badge-primary ms-2">{{ $logs->total() }} bản ghi</span>
                    </h5>
                </div>
                <div class="card-body" style="background: var(--color-bg-2);">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" style="background: var(--color-bg-2) !important;">
                                <thead style="background: var(--color-bg-3) !important;">
                                    <tr>
                                        <th style="color: var(--color-text-secondary) !important; width: 50px;">#</th>
                                        <th style="color: var(--color-text-secondary) !important;">Người dùng</th>
                                        <th style="color: var(--color-text-secondary) !important;">Hành động</th>
                                        <th style="color: var(--color-text-secondary) !important;">Mô tả</th>
                                        <th style="color: var(--color-text-secondary) !important;">IP</th>
                                        <th style="color: var(--color-text-secondary) !important;">Thiết bị</th>
                                        <th style="color: var(--color-text-secondary) !important;">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody style="background: var(--color-bg-2) !important;">
                                    @foreach($logs as $index => $log)
                                        <tr style="background: var(--color-bg-2) !important;">
                                            <td>
                                                {{ $logs->firstItem() + $index }}
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <strong>{{ $log->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $log->user->username }}</small>
                                                @else
                                                    <span class="text-muted">Người dùng đã xóa</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $log->action_badge }}">
                                                    {{ \App\Services\ActivityLogger::getActionName($log->action) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $log->description }}
                                            </td>
                                            <td>
                                                <code>
                                                    {{ $log->ip_address }}
                                                </code>
                                            </td>
                                            <td>
                                                <i class="bi bi-{{ $log->device == 'Mobile' ? 'phone' : ($log->device == 'Tablet' ? 'tablet' : 'laptop') }}"></i>
                                                {{ $log->device }}
                                            </td>
                                            <td>
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                                <br>
                                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox"
                                style="font-size: 3rem; opacity: 0.3; color: var(--color-text-muted);"></i>
                            <p class="text-muted mt-3" style="color: var(--color-text-muted) !important;">
                                @if(request()->hasAny(['user_id', 'action', 'from_date', 'to_date', 'search']))
                                    Không tìm thấy hoạt động nào phù hợp
                                @else
                                    Chưa có nhật ký hoạt động nào
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
