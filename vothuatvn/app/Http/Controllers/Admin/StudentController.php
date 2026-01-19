<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Student;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show form to create new student(s)
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store new student(s)
     */
    public function store(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*.full_name' => 'required|string|max:255',
            'students.*.birth_year' => 'required|integer|min:1950|max:' . date('Y'),
            'students.*.phone' => 'required|string|max:15',
            'students.*.address' => 'required|string',
            'students.*.registration_date' => 'required|date',
        ]);

        foreach ($request->students as $studentData) {
            $student = Student::create([
                'full_name' => $studentData['full_name'],
                'birth_year' => $studentData['birth_year'],
                'phone' => $studentData['phone'],
                'address' => $studentData['address'],
                'registration_date' => $studentData['registration_date'],
                'status' => 'active',
            ]);

            // Log activity for each student
            ActivityLogger::log('created', "Thêm võ sinh {$student->full_name}", Student::class, $student->id);
        }

        $count = count($request->students);
        return redirect()->route('admin.students.index')
            ->with('success', "Thêm thành công {$count} võ sinh!");
    }

    /**
     * Show form to edit student
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_year' => 'required|integer|min:1950|max:' . date('Y'),
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'full_name' => $request->full_name,
            'birth_year' => $request->birth_year,
            'phone' => $request->phone,
            'address' => $request->address,
            'registration_date' => $request->registration_date,
            'status' => $request->status,
        ]);

        // Log activity
        ActivityLogger::log('updated', "Cập nhật võ sinh {$student->full_name}", Student::class, $student->id);

        return redirect()->route('admin.students.index')->with('success', 'Cập nhật võ sinh thành công!');
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Check if student is in any active classes
        if (
            $student->classes()->where('status', 'active')->exists()
        ) {
            return back()->with('error', 'Không thể xóa võ sinh đang học lớp đang hoạt động!');
        }

        $studentName = $student->full_name;
        $student->delete();

        // Log activity
        ActivityLogger::log('deleted', "Xóa võ sinh {$studentName}", Student::class, $id);

        return back()->with('success', 'Xóa võ sinh thành công!');
    }

    /**
     * Import students from Excel data (JSON)
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:1'
        ], [
            'students.required' => 'Không có dữ liệu võ sinh để nhập'
        ]);

        $studentsData = $request->students;
        $importedCount = 0;
        $errors = [];

        foreach ($studentsData as $index => $row) {
            // Expected format via JS: { full_name, birth_year, phone, address }
            $fullName = $row['full_name'] ?? null;
            $birthYear = $row['birth_year'] ?? null;
            $phone = $row['phone'] ?? null;
            $address = $row['address'] ?? null;

            if (empty($fullName) || empty($birthYear) || empty($phone)) {
                $errors[] = "Dòng " . ($index + 1) . ": Thiếu thông tin bắt buộc.";
                continue;
            }

            try {
                $student = Student::create([
                    'full_name' => $fullName,
                    'birth_year' => (int) $birthYear,
                    'phone' => $phone,
                    'address' => $address ?? 'Chưa cập nhật',
                    'registration_date' => now(),
                    'status' => 'active',
                ]);

                ActivityLogger::log('created', "Nhập võ sinh từ Excel: {$student->full_name}", Student::class, $student->id);
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($index + 1) . ": Lỗi khi lưu dữ liệu.";
            }
        }

        if (count($errors) > 0) {
            $msg = "Đã nhập thành công {$importedCount} võ sinh. ";
            $msg .= "Lưu ý: Có " . count($errors) . " lỗi xảy ra.";
            return response()->json([
                'success' => true,
                'message' => $msg,
                'imported_count' => $importedCount,
                'error_count' => count($errors)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Đã nhập thành công {$importedCount} võ sinh từ file Excel!",
            'imported_count' => $importedCount
        ]);
    }

    /**
     * Bulk delete students
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id'
        ]);

        $ids = $request->ids;
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $student = Student::find($id);
            if (!$student)
                continue;

            // Check if student is in any active classes
            if ($student->classes()->where('status', 'active')->exists()) {
                $failCount++;
                $errors[] = "Võ sinh {$student->full_name} đang học lớp đang hoạt động.";
                continue;
            }

            $studentName = $student->full_name;
            $student->delete();

            ActivityLogger::log('deleted', "Xóa nhanh võ sinh {$studentName}", Student::class, $id);
            $successCount++;
        }

        $message = "Đã xóa thành công {$successCount} võ sinh.";
        if ($failCount > 0) {
            $message .= " Thất bại {$failCount} võ sinh (đang tham gia lớp học).";
            return back()->with('warning', $message);
        }

        return back()->with('success', $message);
    }
}
