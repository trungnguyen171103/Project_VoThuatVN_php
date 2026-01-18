@extends('layouts.admin')

@section('title', 'Kết quả tìm kiếm')
@section('page-title', 'Phân quyền HLV')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="user-details">
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Tên đăng nhập:</strong>
                            </div>
                            <div class="col-md-8">
                                <code>{{ $user->username }}</code>
                            </div>
                        </div>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Họ và tên:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Số điện thoại:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->phone ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Vai trò hiện tại:</strong>
                            </div>
                            <div class="col-md-8">
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'coach')
                                    <span class="badge bg-success">HLV</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <strong>Ngày đăng ký:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Trạng thái phân quyền:</strong>
                            </div>
                            <div class="col-md-8">
                                @if($isCoach)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã là HLV</span>
                                @else
                                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Chưa phân
                                        quyền</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$isCoach)
                    <hr>
                    <form action="{{ route('admin.coaches.assign') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle me-2"></i>
                            Bạn có chắc muốn phân quyền HLV cho tài khoản này?
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Phân quyền HLV
                            </button>
                            <a href="{{ route('admin.coaches.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Tài khoản này đã được phân quyền HLV.
                    </div>
                    <a href="{{ route('admin.coaches.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
                    </a>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection