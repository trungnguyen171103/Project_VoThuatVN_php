@extends('layouts.admin')

@section('title', 'Sửa CLB')
@section('page-title', 'Chỉnh Sửa Câu Lạc Bộ')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <form action="{{ route('admin.clubs.update', $club->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Tên CLB <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $club->name) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Chọn HLV</label>
                            <div class="coach-selection-box p-3 rounded" style="background: var(--color-bg-3); border: 1px solid var(--color-border);">
                                <div class="row g-2">
                                    @foreach($coaches as $coach)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="coaches[]" 
                                                    value="{{ $coach->id }}" id="coach{{ $coach->id }}"
                                                    {{ $club->coaches->contains($coach->id) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="coach{{ $coach->id }}" style="color: var(--color-text-primary);">
                                                    {{ $coach->user->name }} 
                                                    <small class="d-block" style="color: var(--color-text-muted);">({{ $coach->user->username }})</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('admin.clubs.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection