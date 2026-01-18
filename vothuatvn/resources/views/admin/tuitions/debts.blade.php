@extends('layouts.admin')

@section('title', 'Danh sách nợ')
@section('page-title', 'Danh Sách Học Phí')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.tuitions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tạo học phí mới
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.tuitions.debts') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Chọn Câu lạc bộ</label>
                                    <select id="clubFilter" name="club_id" class="form-control">
                                        <option value="">-- Tất cả câu lạc bộ --</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                                {{ $club->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Chọn lớp học</label>
                                    <select id="classFilter" name="class_id" class="form-control" {{ !request('club_id') ? 'disabled' : '' }}>
                                        <option value="">-- Chọn câu lạc bộ trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-2"></i>Lọc
                                </button>
                                <a href="{{ route('admin.tuitions.debts') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Xóa bộ lọc
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
            <div class="card animate-fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Danh sách học phí</h5>
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
                                        <th>Thao tác</th>
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
                                                @if($payment->status === 'paid')
                                                    <span class="badge bg-success">Đã đóng</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="badge bg-warning">Chưa đóng</span>
                                                @else
                                                    <span class="badge bg-danger">Quá hạn</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->status !== 'paid')
                                                    <form action="{{ route('admin.tuitions.mark-paid', $payment->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-check-circle"></i> Đánh dấu đã đóng
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('admin.tuitions.print-bill', $payment->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-printer"></i> In bill
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $debts->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Không có dữ liệu</p>
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
    const clubFilter = document.getElementById('clubFilter');
    const classFilter = document.getElementById('classFilter');
    const selectedClassId = '{{ request("class_id") }}';

    // When club is selected, fetch classes
    clubFilter.addEventListener('change', async function() {
        const clubId = this.value;
        
        classFilter.innerHTML = '<option value="">-- Chọn lớp --</option>';
        classFilter.disabled = true;

        if (!clubId) {
            classFilter.innerHTML = '<option value="">-- Chọn câu lạc bộ trước --</option>';
            return;
        }

        try {
            const response = await fetch(`/admin/tuitions/clubs/${clubId}/classes`);
            const classes = await response.json();

            if (classes.length === 0) {
                classFilter.innerHTML = '<option value="">-- Không có lớp nào --</option>';
                return;
            }

            classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = `${cls.name} (${cls.class_code})`;
                if (cls.id == selectedClassId) {
                    option.selected = true;
                }
                classFilter.appendChild(option);
            });

            classFilter.disabled = false;
        } catch (error) {
            console.error('Error fetching classes:', error);
        }
    });

    // Trigger on page load if club is already selected
    if (clubFilter.value) {
        clubFilter.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush