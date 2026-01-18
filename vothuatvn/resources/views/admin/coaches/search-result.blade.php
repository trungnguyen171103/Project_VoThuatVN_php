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
                    <table class="table">
                        <tr>
                            <th width="200">Tên đăng nhập:</th>
                            <td><code>{{ $user->username }}</code></td>
                        </tr>
                        <tr>
                            <th>Họ và tên:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Vai trò hiện tại:</th>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'coach')
                                    <span class="badge bg-success">HLV</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày đăng ký:</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái phân quyền:</th>
                            <td>
                                @if($isCoach)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã là HLV</span>
                                @else
                                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Chưa phân
                                        quyền</span>
                                @endif
                            </td>
                        </tr>
                    </table>

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