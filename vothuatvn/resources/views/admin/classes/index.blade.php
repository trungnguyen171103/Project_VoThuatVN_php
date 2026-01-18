@extends('layouts.admin')

@section('title', 'Quản lý Lớp học')
@section('page-title', 'Quản lý Lớp Học')

@section('content')
    <!-- Filter & Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in">
                <div class="card-body">
                    <form action="{{ route('admin.classes.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Lọc theo CLB</label>
                                    <select name="club_id" class="form-control">
                                        <option value="">Tất cả CLB</option>
                                        @foreach($clubs as $club)
                                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                                {{ $club->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tìm kiếm</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tên lớp hoặc mã lớp..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-search me-2"></i>Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.classes.create') }}" class="btn btn-success flex-fill">
                                        <i class="bi bi-plus-circle me-2"></i>Thêm lớp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes List -->
    <div class="row">
        @forelse($classes as $class)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hover-lift animate-fade-in-up h-100 card-class" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title text-gradient mb-1 fw-bold">{{ $class->name }}</h5>
                                <code class="text-primary bg-primary-soft px-2 py-1 rounded small">{{ $class->class_code }}</code>
                            </div>
                            @if($class->status === 'active')
                                <span class="badge bg-success-soft text-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary-soft text-muted">Kết thúc</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2 text-sm text-secondary">
                                <i class="bi bi-building me-2 text-primary"></i>
                                <span>{{ $class->club->name }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2 text-sm text-secondary">
                                <i class="bi bi-person-badge me-2 text-primary"></i>
                                <span>{{ $class->coach->user->name }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2 text-sm text-secondary">
                                <i class="bi bi-calendar-event me-2 text-primary"></i>
                                <span>{{ $class->start_date->format('d/m/Y') }} @if($class->end_date)- {{ $class->end_date->format('d/m/Y') }}@endif</span>
                            </div>
                        </div>

                        <div class="capacity-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm font-semibold">Sĩ số lớp</span>
                                <span class="badge bg-light text-dark border" id="classCount{{ $class->id }}">
                                    {{ $class->students->count() }}/{{ $class->max_students }}
                                </span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 4px;">
                                <div class="progress-bar {{ $class->students->count() >= $class->max_students ? 'bg-danger' : 'bg-success' }} shadow-none" 
                                    id="progressBar{{ $class->id }}"
                                    role="progressbar" 
                                    style="width: {{ ($class->students->count() / $class->max_students) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('admin.classes.edit', $class->id) }}"
                                class="btn btn-outline-primary flex-fill btn-sm py-2">
                                <i class="bi bi-pencil-square me-1"></i> Sửa
                            </a>
                            <button type="button" class="btn btn-outline-info flex-fill btn-sm py-2" data-bs-toggle="modal"
                                data-bs-target="#studentsModal{{ $class->id }}">
                                <i class="bi bi-people me-1"></i> Võ sinh
                            </button>
                            <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm py-2 px-3"
                                    onclick="handleDeleteForm(this, 'Bạn có chắc muốn xóa lớp <strong>{{ $class->name }}</strong>?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @push('modals')
                <!-- Students Modal -->
                <div class="modal fade" id="studentsModal{{ $class->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-gradient-dark text-white border-0 py-3">
                                <h5 class="modal-title d-flex align-items-center">
                                    <i class="bi bi-people-fill me-2 fs-4"></i>
                                    <span>Võ sinh lớp {{ $class->name }}</span>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="p-4">
                                    <div id="studentTableContainer{{ $class->id }}">
                                        @if($class->students->count() > 0)
                                            <div class="table-responsive border rounded mb-4 shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="bg-light sticky-top">
                                                        <tr>
                                                            <th class="ps-3 border-0">Họ tên</th>
                                                            <th class="border-0">Năm sinh</th>
                                                            <th class="border-0">SĐT</th>
                                                            <th class="text-end pe-3 border-0">Thao tác</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($class->students as $student)
                                                            <tr id="studentRow_{{ $class->id }}_{{ $student->id }}">
                                                                <td class="ps-3 border-0"><strong>{{ $student->full_name }}</strong></td>
                                                                <td class="border-0">{{ $student->birth_year }}</td>
                                                                <td class="border-0"><small class="text-muted">{{ $student->phone ?? '-' }}</small></td>
                                                                <td class="text-end pe-3 border-0">
                                                                    <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger border-0 btn-icon-sm"
                                                                        onclick="removeStudentAjax({{ $class->id }}, {{ $student->id }})">
                                                                        <i class="bi bi-person-dash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-5 opacity-50 bg-light rounded border mb-4 shadow-sm" style="min-height: 150px;">
                                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                                <p class="mb-0">Lớp hiện chưa có nhà võ sinh nào</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-4 border-top">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h6 class="fw-bold mb-0 text-uppercase tracking-wider small text-muted">Thêm võ sinh mới</h6>
                                            <div class="badge bg-primary-soft text-primary px-3 rounded-pill fs-7" id="selectedCount{{ $class->id }}">Đã chọn: 0</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="bi bi-search text-muted"></i>
                                                </span>
                                                <input type="text" id="studentSearch{{ $class->id }}" 
                                                    class="form-control border-start-0" 
                                                    placeholder="Tìm tên võ sinh..." 
                                                    onkeyup="filterStudentCheckboxes({{ $class->id }})">
                                                <button type="button" class="btn btn-outline-secondary" onclick="selectAllStudents({{ $class->id }})">Tất cả</button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="deselectAllStudents({{ $class->id }})">Bỏ chọn</button>
                                            </div>
                                        </div>

                                        <div class="student-checkbox-list scroll-styled" id="studentList{{ $class->id }}" 
                                            style="max-height: 200px; overflow-y: auto; border: 1px solid var(--color-border); border-radius: 12px; padding: 15px; background: var(--color-bg-2);">
                                            @php
                                                $availableStudents = \App\Models\Student::active()->whereNotIn('id', $class->students->pluck('id'))->orderBy('full_name')->get();
                                            @endphp
                                            
                                            <div class="row row-cols-1 row-cols-md-2 g-2">
                                                @forelse($availableStudents as $student)
                                                    <div class="col student-checkbox-item" data-student-name="{{ strtolower($student->full_name) }}">
                                                        <div class="form-check p-2 rounded transition-base student-item-card px-3">
                                                            <input class="form-check-input ms-0 student-checkbox" type="checkbox" 
                                                                name="student_ids[]" value="{{ $student->id }}" 
                                                                id="student{{ $class->id }}_{{ $student->id }}"
                                                                onchange="updateSelectedCount({{ $class->id }})">
                                                            <label class="form-check-label ms-2 d-block cursor-pointer text-truncate" for="student{{ $class->id }}_{{ $student->id }}">
                                                                {{ $student->full_name }} <small class="text-muted">({{ $student->birth_year }})</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12 text-center py-4 text-muted">
                                                        <small>Tất cả học viên hiện tại đã có trong lớp này</small>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="button" onclick="addStudentsAjax({{ $class->id }})" 
                                                class="btn btn-primary w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                                                id="btnAddStudents{{ $class->id }}">
                                                <i class="bi bi-person-plus-fill me-2 fs-5"></i>
                                                <span>THÊM VÕ SINH ĐÃ CHỌN</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endpush
        @empty
            <div class="col-12">
                <div class="card bg-light border-0 py-5 text-center">
                    <div class="opacity-50">
                        <i class="bi bi-book fs-1 d-block mb-3"></i>
                        <p class="mb-0">Chưa tìm thấy lớp học nào</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($classes->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $classes->links() }}
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<style>
    .bg-success-soft { background-color: rgba(34, 197, 94, 0.1); }
    .bg-secondary-soft { background-color: rgba(148, 163, 184, 0.1); }
    .bg-primary-soft { background-color: rgba(59, 130, 246, 0.1); }
    .student-item-card { border: 1px solid transparent; }
    .student-item-card:hover { 
        background-color: var(--color-bg-3); 
        border-color: var(--color-primary-light);
    }
    .btn-white {
        background-color: #fff;
        border: 1px solid #dee2e6;
        color: #212529;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
        color: var(--color-primary);
    }
    .fs-7 { font-size: 0.85rem; }
    .btn-icon-sm { padding: 0.1rem 0.3rem; }
    .tracking-wider { letter-spacing: 0.05em; }
</style>

<script>
    // AJAX Add Students
    async function addStudentsAjax(classId) {
        const btn = document.getElementById('btnAddStudents' + classId);
        const originalHtml = btn.innerHTML;
        const list = document.getElementById('studentList' + classId);
        const selectedCheckboxes = list.querySelectorAll('.student-checkbox:checked');
        const studentIds = Array.from(selectedCheckboxes).map(cb => cb.value);

        if (studentIds.length === 0) {
            alert('Vui lòng chọn ít nhất một võ sinh!');
            return;
        }

        try {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang xử lý...';

            const response = await fetch(`{{ url('admin/classes') }}/${classId}/add-student`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ student_ids: studentIds })
            });

            const data = await response.json();

            if (data.success) {
                // Update table
                refreshStudentLists(classId);
                // Update main sĩ số
                updateMainClassCapacity(classId, data.count);
                // Reset checkboxes
                deselectAllStudents(classId);
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        } catch (error) {
            console.error(error);
            alert('Lỗi kết nối máy chủ!');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    // AJAX Remove Student
    async function removeStudentAjax(classId, studentId) {
        const confirmed = await confirmDelete('Bạn có chắc muốn xóa võ sinh này khỏi lớp?');
        if (!confirmed) return;

        try {
            const response = await fetch(`{{ url('admin/classes') }}/${classId}/remove-student/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update UI
                const row = document.getElementById(`studentRow_${classId}_${studentId}`);
                if (row) row.remove();
                
                refreshStudentLists(classId);
                updateMainClassCapacity(classId, data.count);
            } else {
                Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra!', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Lỗi!', 'Lỗi kết nối máy chủ!', 'error');
        }
    }

    // Refresh list in modal
    async function refreshStudentLists(classId) {
        try {
            // Re-fetch current students table
            const tableRes = await fetch(`{{ url('admin/classes') }}/${classId}/students`);
            const students = await tableRes.json();
            
            const tableContainer = document.getElementById('studentTableContainer' + classId);
            if (students.length > 0) {
                let html = `
                    <div class="table-responsive border rounded mb-4 shadow-sm h-100" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-3 border-0">Họ tên</th>
                                    <th class="border-0">Năm sinh</th>
                                    <th class="border-0">SĐT</th>
                                    <th class="text-end pe-3 border-0">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>`;
                
                students.forEach(student => {
                    html += `
                        <tr id="studentRow_${classId}_${student.id}">
                            <td class="ps-3 border-0"><strong>${student.full_name}</strong></td>
                            <td class="border-0">${student.birth_year}</td>
                            <td class="border-0"><small class="text-muted">${student.phone || '-'}</small></td>
                            <td class="text-end pe-3 border-0">
                                <button type="button" 
                                    class="btn btn-sm btn-outline-danger border-0 btn-icon-sm"
                                    onclick="removeStudentAjax(${classId}, ${student.id})">
                                    <i class="bi bi-person-dash"></i>
                                </button>
                            </td>
                        </tr>`;
                });
                
                html += `</tbody></table></div>`;
                tableContainer.innerHTML = html;
            } else {
                tableContainer.innerHTML = `
                    <div class="text-center py-5 opacity-50 bg-light rounded border mb-4 shadow-sm h-100" style="min-height: 150px;">
                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                        <p class="mb-0">Lớp hiện chưa có nhà võ sinh nào</p>
                    </div>`;
            }

            // Re-fetch available students list
            const availRes = await fetch(`{{ url('admin/classes') }}/${classId}/available-students`);
            const available = await availRes.json();
            
            const listContainer = document.getElementById('studentList' + classId);
            if (available.length > 0) {
                let html = '<div class="row row-cols-1 row-cols-md-2 g-2">';
                available.forEach(student => {
                    html += `
                        <div class="col student-checkbox-item" data-student-name="${student.full_name.toLowerCase()}">
                            <div class="form-check p-2 rounded transition-base student-item-card">
                                <input class="form-check-input ms-0 student-checkbox" type="checkbox" 
                                    name="student_ids[]" value="${student.id}" 
                                    id="student${classId}_${student.id}"
                                    onchange="updateSelectedCount(${classId})">
                                <label class="form-check-label ms-2 d-block cursor-pointer text-truncate" for="student${classId}_${student.id}">
                                    ${student.full_name} <small class="text-muted">(${student.birth_year})</small>
                                </label>
                            </div>
                        </div>`;
                });
                html += '</div>';
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = `
                    <div class="col-12 text-center py-4 text-muted">
                        <small>Tất cả học viên hiện tại đã có trong lớp này</small>
                    </div>`;
            }
            updateSelectedCount(classId);
        } catch (error) {
            console.error(error);
        }
    }

    // Update sĩ số on main screen
    function updateMainClassCapacity(classId, currentCount) {
        const countBadge = document.getElementById('classCount' + classId);
        const bar = document.getElementById('progressBar' + classId);
        
        if (countBadge) {
            const parts = countBadge.textContent.split('/');
            const max = parts[1];
            countBadge.textContent = `${currentCount}/${max}`;
            
            if (bar) {
                const percent = (currentCount / max) * 100;
                bar.style.width = percent + '%';
                if (currentCount >= max) {
                    bar.className = 'progress-bar bg-danger';
                } else {
                    bar.className = 'progress-bar bg-success';
                }
            }
        }
    }

    // UI Helpers
    function filterStudentCheckboxes(classId) {
        const input = document.getElementById('studentSearch' + classId);
        const filter = input.value.toLowerCase();
        const list = document.getElementById('studentList' + classId);
        const items = list.getElementsByClassName('student-checkbox-item');

        for (let i = 0; i < items.length; i++) {
            const studentName = items[i].getAttribute('data-student-name');
            items[i].style.display = studentName.includes(filter) ? "" : "none";
        }
    }

    function selectAllStudents(classId) {
        const list = document.getElementById('studentList' + classId);
        const checkboxes = list.querySelectorAll('.student-checkbox-item:not([style*="display: none"]) .student-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
        updateSelectedCount(classId);
    }

    function deselectAllStudents(classId) {
        const list = document.getElementById('studentList' + classId);
        const checkboxes = list.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedCount(classId);
    }

    function updateSelectedCount(classId) {
        const list = document.getElementById('studentList' + classId);
        const checkboxes = list.querySelectorAll('.student-checkbox:checked');
        const countDisplay = document.getElementById('selectedCount' + classId);
        if (countDisplay) countDisplay.textContent = 'Đã chọn: ' + checkboxes.length;
    }
</script>
@endpush
