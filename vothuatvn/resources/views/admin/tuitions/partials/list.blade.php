<div class="row mt-4">
    <div class="col-md-10 mx-auto">
        <div class="card animate-scale-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Danh sách học phí đã tạo</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>CLB</th>
                                <th>Lớp</th>
                                <th>Huấn luyện viên</th>
                                <th>Thời gian học</th>
                                <th>Giá tiền</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($existingTuitions as $tuition)
                                <tr>
                                    <td>{{ $tuition->classModel->club->name }}</td>
                                    <td>
                                        <strong>{{ $tuition->classModel->name }}</strong><br>
                                        <small class="text-muted">{{ $tuition->classModel->class_code }}</small>
                                    </td>
                                    <td>{{ $tuition->classModel->coach ? $tuition->classModel->coach->user->name : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info text-dark">
                                            Tháng {{ $tuition->start_month }}/{{ $tuition->start_year }} -
                                            {{ $tuition->end_month }}/{{ $tuition->end_year }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($tuition->amount) }} VNĐ</strong>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-tuition-btn"
                                                data-id="{{ $tuition->class_id }}"
                                                data-class="{{ $tuition->classModel->name }}"
                                                data-amount="{{ $tuition->amount }}">
                                                <i class="bi bi-pencil"></i> Sửa
                                            </button>
                                            <form
                                                action="{{ route('admin.tuitions.destroy-by-class', $tuition->class_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="handleDeleteForm(this, 'Bạn có chắc chắn muốn xoá toàn bộ học phí của lớp <strong>{{ $tuition->classModel->name }}</strong>? Điều này sẽ xoá tất cả tháng học phí chưa thanh toán.')">
                                                    <i class="bi bi-trash"></i> Xoá
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có học phí nào được tạo</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>