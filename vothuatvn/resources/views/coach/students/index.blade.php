@extends('layouts.admin')

@section('title', 'Danh sách võ sinh')
@section('page-title', 'Danh Sách Võ Sinh')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Năm sinh</th>
                                        <th>SĐT</th>
                                        <th>Lớp học</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                                            <td><strong>{{ $student->full_name }}</strong></td>
                                            <td>{{ $student->birth_year }}</td>
                                            <td>{{ $student->phone ?? '-' }}</td>
                                            <td>
                                                @foreach($student->classes as $class)
                                                    <span class="badge bg-primary me-1">{{ $class->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('coach.students.show', $student->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $students->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Chưa có võ sinh nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection