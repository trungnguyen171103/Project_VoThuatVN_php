@extends('layouts.admin')

@section('title', 'Tạo học phí')
@section('page-title', 'Tạo Học Phí')

@section('content')
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.tuitions.store') }}" method="POST" id="tuitionForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Chọn Câu lạc bộ <span class="text-danger">*</span></label>
                                    <select id="clubSelect" class="form-control" required>
                                        <option value="">-- Chọn câu lạc bộ --</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}">{{ $club->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Chọn lớp học <span class="text-danger">*</span></label>
                                    <select name="class_id" id="classSelect" class="form-control" required disabled>
                                        <option value="">-- Chọn câu lạc bộ trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Huấn luyện viên</label>
                                    <input type="text" id="coachName" class="form-control" readonly
                                        placeholder="Chọn lớp để xem HLV">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Thời gian khóa học</label>
                                    <input type="text" id="courseDuration" class="form-control" readonly
                                        placeholder="Chọn lớp để xem thời gian">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Số tiền (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" min="0" step="1000" required>
                                    <small class="text-muted">Hệ thống sẽ tự động tạo học phí cho tất cả các tháng trong
                                        khoảng thời gian lớp học hoạt động</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Tạo học phí
                            </button>
                            <a href="{{ route('admin.tuitions.debts') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.tuitions.partials.list')
@endsection

@push('modals')
    <!-- Edit Tuition Modal -->
    <div class="modal fade" id="editTuitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editTuitionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật học phí</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <span class="text-muted d-block mb-1">Cập nhật cho lớp:</span>
                            <h4 id="editClassName" class="text-primary mb-0"></h4>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số tiền mới (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" id="editAmount" class="form-control form-control-lg"
                                    required min="0" step="1000">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                            <div class="mt-2 p-2 bg-light rounded bg-opacity-10">
                                <small class="text-warning">
                                    <i class="bi bi-info-circle me-1"></i> Lưu ý: Mức giá mới chỉ áp dụng cho các học sinh ở
                                    trạng thái <strong>Chưa nộp tiền</strong>.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clubSelect = document.getElementById('clubSelect');
            const classSelect = document.getElementById('classSelect');
            const coachName = document.getElementById('coachName');
            const courseDuration = document.getElementById('courseDuration');

            console.log('Tuition form initialized');
            console.log('Elements found:', {
                clubSelect: !!clubSelect,
                classSelect: !!classSelect,
                coachName: !!coachName,
                courseDuration: !!courseDuration
            });

            // When club is selected, fetch classes
            clubSelect.addEventListener('change', async function () {
                const clubId = this.value;
                console.log('Club selected, ID:', clubId);

                // Reset class select and info fields
                classSelect.innerHTML = '<option value="">-- Chọn lớp --</option>';
                classSelect.disabled = true;
                coachName.value = '';
                courseDuration.value = '';

                if (!clubId) {
                    console.log('No club selected, returning');
                    return;
                }

                try {
                    const url = `/admin/tuitions/clubs/${clubId}/classes`;
                    console.log('Fetching from URL:', url);

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    console.log('Response received:', {
                        status: response.status,
                        statusText: response.statusText,
                        ok: response.ok
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Response error:', errorText);
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const classes = await response.json();
                    console.log('Classes data:', classes);
                    console.log('Number of classes:', classes.length);

                    if (classes.length === 0) {
                        console.log('No classes found for this club');
                        classSelect.innerHTML = '<option value="">-- Không có lớp nào --</option>';
                        return;
                    }

                    console.log('Populating class dropdown...');
                    classes.forEach((cls, index) => {
                        console.log(`Adding class ${index + 1}:`, cls);
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = `${cls.name} (${cls.class_code})`;
                        classSelect.appendChild(option);
                    });

                    classSelect.disabled = false;
                    console.log('Class dropdown enabled successfully');
                } catch (error) {
                    console.error('Error details:', error);
                    alert('Có lỗi khi tải danh sách lớp học:\n' + error.message + '\n\nVui lòng mở Console (F12) để xem chi tiết lỗi.');
                }
            });

            // When class is selected, fetch class details
            classSelect.addEventListener('change', async function () {
                const classId = this.value;
                console.log('Class selected, ID:', classId);

                // Reset info fields
                coachName.value = '';
                courseDuration.value = '';

                if (!classId) {
                    console.log('No class selected, returning');
                    return;
                }

                try {
                    const url = `/admin/tuitions/classes/${classId}/details`;
                    console.log('Fetching class details from:', url);

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    console.log('Class details response:', {
                        status: response.status,
                        ok: response.ok
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Response error:', errorText);
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const details = await response.json();
                    console.log('Class details:', details);

                    coachName.value = details.coach_name;

                    if (details.start_date && details.end_date) {
                        courseDuration.value = `${details.start_date} - ${details.end_date}`;
                    } else {
                        courseDuration.value = 'Chưa thiết lập';
                    }

                    console.log('Class details populated successfully');
                } catch (error) {
                    console.error('Error fetching class details:', error);
                    alert('Có lỗi khi tải thông tin lớp học:\n' + error.message);
                }
            });

            // Edit tuition modal handling
            const editButtons = document.querySelectorAll('.edit-tuition-btn');
            const editModal = new bootstrap.Modal(document.getElementById('editTuitionModal'));
            const editForm = document.getElementById('editTuitionForm');
            const editClassName = document.getElementById('editClassName');
            const editAmount = document.getElementById('editAmount');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const classId = this.getAttribute('data-id');
                    const className = this.getAttribute('data-class');
                    const currentAmount = this.getAttribute('data-amount');

                    editClassName.textContent = className;
                    editAmount.value = currentAmount;
                    editForm.action = `/admin/tuitions/class/${classId}`;

                    editModal.show();
                });
            });
        });
    </script>
@endpush