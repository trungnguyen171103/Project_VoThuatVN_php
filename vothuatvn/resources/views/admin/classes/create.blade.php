@extends('layouts.admin')

@section('title', 'Tạo lớp học')
@section('page-title', 'Tạo Lớp Học Mới')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <form action="{{ route('admin.classes.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Câu lạc bộ <span class="text-danger">*</span></label>
                                    <select name="club_id" id="club_id" class="form-control" required>
                                        <option value="">-- Chọn CLB --</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                                                {{ $club->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Huấn luyện viên <span class="text-danger">*</span></label>
                                    <select name="coach_id" id="coach_id" class="form-control" required>
                                        <option value="">-- Chọn CLB trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tên lớp học <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            <small class="text-muted">Mã lớp sẽ được tạo tự động</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sĩ số tối đa <span class="text-danger">*</span></label>
                                    <input type="number" name="max_students" class="form-control"
                                        value="{{ old('max_students', 20) }}" min="1" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Tạo lớp học
                            </button>
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('club_id').addEventListener('change', function () {
            const clubId = this.value;
            const coachSelect = document.getElementById('coach_id');

            coachSelect.innerHTML = '<option value="">-- Đang tải... --</option>';

            if (clubId) {
                fetch(`/admin/clubs/${clubId}/coaches`)
                    .then(response => response.json())
                    .then(coaches => {
                        coachSelect.innerHTML = '<option value="">-- Chọn HLV --</option>';
                        coaches.forEach(coach => {
                            const option = document.createElement('option');
                            option.value = coach.id;
                            option.textContent = `${coach.user.name} (${coach.user.username})`;
                            coachSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        coachSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
                    });
            } else {
                coachSelect.innerHTML = '<option value="">-- Chọn CLB trước --</option>';
            }
        });
    </script>
@endsection