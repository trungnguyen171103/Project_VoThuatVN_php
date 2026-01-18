@extends('layouts.admin')

@section('title', 'Tạo lịch học')
@section('page-title', 'Tạo Lịch Học Mới')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Chọn CLB <span class="text-danger">*</span></label>
                            <select name="club_id" id="club_id" class="form-control" required>
                                <option value="">-- Chọn CLB --</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Chọn lớp học <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-control" required>
                                <option value="">-- Chọn CLB trước --</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Chọn thứ trong tuần <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="btn-group" role="group">
                                    <input type="checkbox" class="btn-check" name="days[]" value="1" id="day1"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day1">Thứ 2</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="2" id="day2"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day2">Thứ 3</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="3" id="day3"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day3">Thứ 4</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="4" id="day4"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day4">Thứ 5</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="5" id="day5"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day5">Thứ 6</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="6" id="day6"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day6">Thứ 7</label>

                                    <input type="checkbox" class="btn-check" name="days[]" value="0" id="day0"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="day0">Chủ nhật</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" class="form-control" required lang="en-US"
                                        step="300">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" class="form-control" required lang="en-US"
                                        step="300">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label class="form-label">Huấn luyện viên (Theo lớp)</label>
                            <input type="text" id="coach_display" class="form-control" readonly
                                placeholder="Tự động hiển thị khi chọn lớp...">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Tạo lịch học
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
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
            const classSelect = document.getElementById('class_id');

            if (clubId) {
                fetch(`/admin/schedules/clubs/${clubId}/classes`)
                    .then(response => response.json())
                    .then(classes => {
                        classSelect.innerHTML = '<option value="">-- Chọn lớp --</option>';
                        classes.forEach(cls => {
                            const coachName = cls.coach && cls.coach.user ? cls.coach.user.name : 'Chưa cập nhật';
                            classSelect.innerHTML += `<option value="${cls.id}" data-coach="${coachName}">${cls.name} (${cls.class_code})</option>`;
                        });
                    });
            } else {
                classSelect.innerHTML = '<option value="">-- Chọn CLB trước --</option>';
            }
        });

        document.getElementById('class_id').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const coachName = selectedOption.getAttribute('data-coach');
            document.getElementById('coach_display').value = coachName || '';
        });
    </script>
@endsection