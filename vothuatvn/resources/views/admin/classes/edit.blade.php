@extends('layouts.admin')

@section('title', 'Sửa lớp học')
@section('page-title', 'Chỉnh Sửa Lớp Học')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>Mã lớp:</strong> <code>{{ $class->class_code }}</code>
                    </div>

                    <form action="{{ route('admin.classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Câu lạc bộ <span class="text-danger">*</span></label>
                                    <select name="club_id" id="club_id" class="form-control" required>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ old('club_id', $class->club_id) == $club->id ? 'selected' : '' }}>
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
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ old('coach_id', $class->coach_id) == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->user->name }} ({{ $coach->user->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tên lớp học <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control"
                                rows="3">{{ old('description', $class->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Sĩ số tối đa <span class="text-danger">*</span></label>
                                    <input type="number" name="max_students" class="form-control"
                                        value="{{ old('max_students', $class->max_students) }}" min="1" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date', $class->start_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date', $class->end_date?->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ old('status', $class->status) === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status', $class->status) === 'inactive' ? 'selected' : '' }}>Kết thúc</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật
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
            }
        });
    </script>
@endsection