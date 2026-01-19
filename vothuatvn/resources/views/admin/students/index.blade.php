@extends('layouts.admin')

@section('title', 'Quản lý Võ sinh')
@section('page-title', 'Quản lý Võ Sinh')

@section('content')
    <!-- Search & Add & Bulk Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card animate-fade-in">
                <div class="card-body">
                    <form action="{{ route('admin.students.index') }}" method="GET" id="searchForm">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tìm kiếm</label>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tên hoặc số điện thoại..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-2"></i>Tìm kiếm
                                        </button>
                                        <a href="{{ route('admin.students.create') }}" class="btn btn-success">
                                            <i class="bi bi-plus-circle me-2"></i>Thêm võ sinh
                                        </a>
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                            data-bs-target="#importModal">
                                            <i class="bi bi-file-earmark-excel me-2"></i>Nhập EXCEL
                                        </button>
                                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none"
                                            onclick="handleBulkDelete()">
                                            <i class="bi bi-trash me-2"></i>Xóa đã chọn (<span id="selectedCount">0</span>)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <form id="bulkDeleteForm" action="{{ route('admin.students.bulk-destroy') }}" method="POST">
                @csrf
                <div class="card animate-fade-in-up" style="animation-delay: 0.1s">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Danh sách Võ sinh</h5>
                        <span class="badge bg-primary">{{ $students->total() }} võ sinh</span>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </th>
                                            <th>STT</th>
                                            <th>Họ và tên</th>
                                            <th>Năm sinh</th>
                                            <th>Số điện thoại</th>
                                            <th>Địa chỉ</th>
                                            <th>Ngày đăng ký</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $index => $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{ $student->id }}"
                                                        class="form-check-input student-checkbox">
                                                </td>
                                                <td>{{ $students->firstItem() + $index }}</td>
                                                <td><strong>{{ $student->full_name }}</strong></td>
                                                <td>{{ $student->birth_year }}</td>
                                                <td>{{ $student->phone }}</td>
                                                <td>{{ Str::limit($student->address, 30) }}</td>
                                                <td>{{ $student->registration_date ? \Carbon\Carbon::parse($student->registration_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>
                                                    @if ($student->status === 'active')
                                                        <span class="badge bg-success">Đang học</span>
                                                    @else
                                                        <span class="badge bg-secondary">Nghỉ học</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteSingleStudent({{ $student->id }}, '{{ $student->full_name }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $students->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có võ sinh nào</p>
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm võ sinh đầu tiên
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Single Delete Form (Hidden) -->
    <form id="singleDeleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('modals')
    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: white; color: #333 text-shadow: none;">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" style="color: #333;">Nhập Võ Sinh từ EXCEL (.xlsx, .xls)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2" style="background: #e7f3ff; border-color: #b6d4fe; color: #084298;">
                        <h6 class="fw-bold mb-2 small"><i class="bi bi-info-circle me-2"></i>Hướng dẫn:</h6>
                        <ul class="mb-0 small ps-3">
                            <li>Hỗ trợ file Excel định dạng <strong>.xlsx</strong> hoặc <strong>.xls</strong>.</li>
                            <li>Cấu trúc 4 cột: <strong>Họ tên, Năm sinh, SĐT, Địa chỉ</strong>.</li>
                            <li>Dòng đầu tiên trong file sẽ được hệ thống bỏ qua (dòng tiêu đề).</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: #333;">Chọn file EXCEL</label>
                        <input type="file" id="excelFile" class="form-control" accept=".xlsx, .xls" required>
                    </div>
                    <div id="importProgress" class="d-none mt-3 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 mb-0">Đang xử lý dữ liệu...</p>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" id="startImportBtn" class="btn btn-success">
                        <i class="bi bi-upload me-2"></i>Bắt đầu nhập
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <!-- SheetJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        // --- Bulk Actions Logic ---
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.student-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCountSpan = document.getElementById('selectedCount');

        function updateBulkActions() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkActions();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateBulkActions();
                if (!this.checked) {
                    selectAll.checked = false;
                } else if (document.querySelectorAll('.student-checkbox:checked').length === checkboxes.length) {
                    selectAll.checked = true;
                }
            });
        });

        async function handleBulkDelete() {
            const confirmed = await confirmDelete('Bạn có chắc muốn xóa các võ sinh đã chọn? Hành động này không thể hoàn tác.', 'Xác nhận xóa hàng loạt');
            if (confirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        }

        function deleteSingleStudent(id, name) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/admin/students/${id}`;
            handleDeleteForm(form.querySelector('button') || { closest: () => form }, `Bạn có chắc muốn xóa võ sinh <strong>${name}</strong>?`);
        }

        // --- Import Logic ---
        document.getElementById('startImportBtn').addEventListener('click', function () {
            const fileInput = document.getElementById('excelFile');
            if (fileInput.files.length === 0) {
                Swal.fire('Lỗi', 'Vui lòng chọn file Excel', 'error');
                return;
            }

            const file = fileInput.files[0];
            const reader = new FileReader();

            document.getElementById('importProgress').classList.remove('d-none');
            this.disabled = true;

            reader.onload = function (e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                        header: 1
                    });

                    if (jsonData.length <= 1) {
                        throw new Error('File không có dữ liệu');
                    }

                    const headerRow = jsonData[0];
                    let startIndex = 0;
                    if (headerRow && (headerRow[0] === 'STT' || headerRow[0] === 'stt')) {
                        startIndex = 1;
                    } else if (jsonData[1] && typeof jsonData[1][0] === 'number' && typeof jsonData[1][1] === 'string') {
                        startIndex = 1;
                    }

                    const students = [];
                    for (let i = 1; i < jsonData.length; i++) {
                        const row = jsonData[i];
                        if (row.length === 0) continue;
                        const fullName = row[startIndex];
                        if (!fullName) continue;

                        students.push({
                            full_name: fullName,
                            birth_year: row[startIndex + 1],
                            phone: row[startIndex + 2],
                            address: row[startIndex + 3] || ''
                        });
                    }

                    if (students.length === 0) {
                        throw new Error('Không tìm thấy dữ liệu võ sinh hợp lệ');
                    }

                    sendDataToServer(students);

                } catch (error) {
                    Swal.fire('Lỗi', error.message, 'error');
                    resetModal();
                }
            };

            reader.readAsArrayBuffer(file);
        });

        function sendDataToServer(students) {
            fetch('{{ route('admin.students.import-excel') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    students: students
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Thành công',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                        resetModal();
                    }
                })
                .catch(error => {
                    Swal.fire('Lỗi', 'Không thể kết nối đến máy chủ', 'error');
                    resetModal();
                });
        }

        function resetModal() {
            document.getElementById('importProgress').classList.add('d-none');
            document.getElementById('startImportBtn').disabled = false;
        }
    </script>
@endpush