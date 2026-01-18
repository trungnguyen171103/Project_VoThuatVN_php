<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
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
            Student::create([
                'full_name' => $studentData['full_name'],
                'birth_year' => $studentData['birth_year'],
                'phone' => $studentData['phone'],
                'address' => $studentData['address'],
                'registration_date' => $studentData['registration_date'],
                'status' => 'active',
            ]);
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

        $student->delete();

        return back()->with('success', 'Xóa võ sinh thành công!');
    }
}
