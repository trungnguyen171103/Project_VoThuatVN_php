@extends('layouts.admin')

@section('title', 'Sửa Võ sinh')
@section('page-title', 'Chỉnh Sửa Thông Tin Võ Sinh')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card animate-scale-in">
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="{{ old('full_name', $student->full_name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Năm sinh <span class="text-danger">*</span></label>
                                    <input type="number" name="birth_year" class="form-control"
                                        value="{{ old('birth_year', $student->birth_year) }}" min="1950"
                                        max="{{ date('Y') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="{{ old('phone', $student->phone) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ngày đăng ký <span class="text-danger">*</span></label>
                                    <input type="date" name="registration_date" class="form-control"
                                        value="{{ old('registration_date', $student->registration_date ? \Carbon\Carbon::parse($student->registration_date)->format('Y-m-d') : '') }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2"
                                required>{{ old('address', $student->address) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>
                                    Đang học</option>
                                <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Nghỉ học</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật
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