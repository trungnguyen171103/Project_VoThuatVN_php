@extends('layouts.admin')

@section('title', 'Nợ học phí')
@section('page-title', 'Danh Sách Nợ Học Phí')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('coach.debts.index') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Lọc theo lớp học</label>
                                <select name="class_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Tất cả lớp học --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->club->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <a href="{{ route('coach.debts.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Danh sách nợ học phí</h5>
                </div>
                <div class="card-body">
                    @if($debts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Võ sinh</th>
                                        <th>Lớp học</th>
                                        <th>Tháng</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($debts as $payment)
                                        <tr>
                                            <td><strong>{{ $payment->student->full_name }}</strong></td>
                                            <td>{{ $payment->tuition->classModel->name }}</td>
                                            <td>{{ str_pad($payment->tuition->month, 2, '0', STR_PAD_LEFT) }}/{{ $payment->tuition->year }}</td>
                                            <td><span class="text-danger fw-bold">{{ number_format($payment->amount) }} đ</span></td>
                                            <td>
                                                @if($payment->status === 'pending')
                                                    <span class="badge bg-warning">Chưa đóng</span>
                                                @else
                                                    <span class="badge bg-danger">Quá hạn</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $debts->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3; color: #22c55e;"></i>
                            <p class="text-muted mt-3">Không có võ sinh nợ học phí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
