@extends('layouts.admin')

@section('title', 'Quản lý CLB')
@section('page-title', 'Quản lý Câu Lạc Bộ')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.clubs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm CLB mới
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($clubs as $club)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hover-lift animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card-body">
                        <h5 class="card-title text-gradient">{{ $club->name }}</h5>


                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">
                                <i class="bi bi-people me-1"></i>{{ $club->coaches->count() }} HLV
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-book me-1"></i>{{ $club->classes->count() }} Lớp
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.clubs.edit', $club->id) }}"
                                class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form action="{{ route('admin.clubs.destroy', $club->id) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                    onclick="handleDeleteForm(this, 'Bạn có chắc muốn xóa câu lạc bộ <strong>{{ $club->name }}</strong>?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Chưa có CLB nào</p>
                        <a href="{{ route('admin.clubs.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tạo CLB đầu tiên
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($clubs->hasPages())
        <div class="row">
            <div class="col-12">
                {{ $clubs->links() }}
            </div>
        </div>
    @endif
@endsection