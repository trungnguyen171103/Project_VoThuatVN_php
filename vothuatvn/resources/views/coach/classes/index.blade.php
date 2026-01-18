@extends('layouts.admin')

@section('title', 'Lớp học của tôi')
@section('page-title', 'Lớp Học Của Tôi')

@section('content')
    <div class="row">
        @forelse($classes as $class)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hover-lift animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title text-gradient mb-1">{{ $class->name }}</h5>
                                <code class="text-muted">{{ $class->class_code }}</code>
                            </div>
                            @if($class->status === 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Kết thúc</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <p class="mb-2 text-sm">
                                <i class="bi bi-building me-2 text-primary"></i>{{ $class->club->name }}
                            </p>
                            <p class="mb-2 text-sm">
                                <i class="bi bi-people me-2 text-primary"></i>
                                {{ $class->students_count }}/{{ $class->max_students }} võ sinh
                            </p>
                            <p class="mb-0 text-sm">
                                <i class="bi bi-calendar me-2 text-primary"></i>
                                {{ $class->start_date->format('d/m/Y') }}
                                @if($class->end_date)
                                    - {{ $class->end_date->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('coach.classes.show', $class->id) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-book" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Bạn chưa được phân công lớp học nào</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($classes->hasPages())
        <div class="row">
            <div class="col-12">
                {{ $classes->links() }}
            </div>
        </div>
    @endif
@endsection