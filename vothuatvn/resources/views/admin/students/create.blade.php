@extends('layouts.admin')

@section('title', 'Thêm Võ sinh')
@section('page-title', 'Thêm Võ Sinh')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Bạn có thể thêm nhiều võ sinh cùng lúc bằng cách click "Thêm võ sinh khác"
                    </div>

                    <form action="{{ route('admin.students.store') }}" method="POST" id="studentForm">
                        @csrf

                        <div id="studentsContainer">
                            <div class="student-form-group mb-4 p-3"
                                style="background: rgba(255,255,255,0.05); border-radius: 10px;">
                                <h6 class="text-gradient mb-3">Võ sinh #1</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" name="students[0][full_name]" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Năm sinh <span class="text-danger">*</span></label>
                                            <input type="number" name="students[0][birth_year]" class="form-control"
                                                min="1950" max="{{ date('Y') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Số điện thoại <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="students[0][phone]" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Ngày đăng ký <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="students[0][registration_date]" class="form-control"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                    <textarea name="students[0][address]" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-secondary" id="addMoreBtn">
                                <i class="bi bi-plus-circle me-2"></i>Thêm võ sinh khác
                            </button>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Lưu tất cả
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
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
        let studentCount = 1;

        document.getElementById('addMoreBtn').addEventListener('click', function () {
            const container = document.getElementById('studentsContainer');
            const newStudent = document.createElement('div');
            newStudent.className = 'student-form-group mb-4 p-3 animate-fade-in-up';
            newStudent.style.cssText = 'background: rgba(255,255,255,0.05); border-radius: 10px; position: relative;';

            newStudent.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger" style="position: absolute; top: 10px; right: 10px;" onclick="this.closest('.student-form-group').remove()">
                <i class="bi bi-x"></i>
            </button>

            <h6 class="text-gradient mb-3">Võ sinh #${studentCount + 1}</h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="students[${studentCount}][full_name]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Năm sinh <span class="text-danger">*</span></label>
                        <input type="number" name="students[${studentCount}][birth_year]" class="form-control" 
                               min="1950" max="${new Date().getFullYear()}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="students[${studentCount}][phone]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ngày đăng ký <span class="text-danger">*</span></label>
                        <input type="date" name="students[${studentCount}][registration_date]" class="form-control" 
                               value="${new Date().toISOString().split('T')[0]}" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                <textarea name="students[${studentCount}][address]" class="form-control" rows="2" required></textarea>
            </div>
        `;

            container.appendChild(newStudent);
            studentCount++;
        });
    </script>
@endsection