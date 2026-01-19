<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Club;
use App\Models\Coach;
use App\Models\Student;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display all classes
     */
    public function index(Request $request)
    {
        $clubs = Club::active()->get();
        $query = ClassModel::with('club', 'coach.user', 'students');

        // Filter by club
        if ($request->club_id) {
            $query->where('club_id', $request->club_id);
        }

        // Search by name or code
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ILIKE', '%' . $request->search . '%')
                    ->orWhere('class_code', 'ILIKE', '%' . $request->search . '%');
            });
        }

        $classes = $query->paginate(15);

        return view('admin.classes.index', compact('classes', 'clubs'));
    }

    /**
     * Show form to create new class
     */
    public function create()
    {
        $clubs = Club::active()->get();
        return view('admin.classes.create', compact('clubs'));
    }

    /**
     * Get coaches for selected club
     */
    public function getClubCoaches($clubId)
    {
        $coaches = Coach::whereHas('clubs', function ($q) use ($clubId) {
            $q->where('clubs.id', $clubId);
        })->with('user')->get();

        return response()->json($coaches);
    }

    /**
     * Store new class
     */
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'coach_id' => 'required|exists:coaches,id',
            'description' => 'nullable|string',
            'max_students' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $class = ClassModel::create([
            'club_id' => $request->club_id,
            'name' => $request->name,
            'coach_id' => $request->coach_id,
            'description' => $request->description,
            'max_students' => $request->max_students,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        // Log activity
        ActivityLogger::log('created', "Tạo lớp học {$class->name}", ClassModel::class, $class->id);

        return redirect()->route('admin.classes.index')->with('success', 'Tạo lớp học thành công!');
    }

    /**
     * Show form to edit class
     */
    public function edit($id)
    {
        $class = ClassModel::with('club')->findOrFail($id);
        $clubs = Club::active()->get();
        $coaches = Coach::whereHas('clubs', function ($q) use ($class) {
            $q->where('clubs.id', $class->club_id);
        })->with('user')->get();

        return view('admin.classes.edit', compact('class', 'clubs', 'coaches'));
    }

    /**
     * Update class
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'coach_id' => 'required|exists:coaches,id',
            'description' => 'nullable|string',
            'max_students' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $class = ClassModel::findOrFail($id);

        $class->update([
            'club_id' => $request->club_id,
            'name' => $request->name,
            'coach_id' => $request->coach_id,
            'description' => $request->description,
            'max_students' => $request->max_students,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        // Log activity
        ActivityLogger::log('updated', "Cập nhật lớp học {$class->name}", ClassModel::class, $class->id);

        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp học thành công!');
    }

    /**
     * Delete class
     */
    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $className = $class->name;
        $class->delete();

        // Log activity
        ActivityLogger::log('deleted', "Xóa lớp học {$className}", ClassModel::class, $id);

        return back()->with('success', 'Xóa lớp học thành công!');
    }

    /**
     * Get students in class
     */
    public function getStudents($id)
    {
        $class = ClassModel::with('students')->findOrFail($id);
        return response()->json($class->students);
    }

    public function addStudent(Request $request, $id)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $class = ClassModel::findOrFail($id);
        $studentIds = $request->student_ids;

        // Check if class will exceed max students
        $currentCount = $class->students()->count();
        $newCount = $currentCount + count($studentIds);

        if ($newCount > $class->max_students) {
            $available = $class->max_students - $currentCount;
            return back()->with('error', "Lớp học chỉ còn chỗ cho {$available} võ sinh!");
        }

        // Filter out students already in class
        $existingIds = $class->students()->pluck('students.id')->toArray();
        $newStudentIds = array_diff($studentIds, $existingIds);

        if (empty($newStudentIds)) {
            return back()->with('error', 'Tất cả võ sinh đã có trong lớp!');
        }

        // Add students to class
        $class->students()->attach($newStudentIds);

        $addedCount = count($newStudentIds);
        $skippedCount = count($studentIds) - $addedCount;

        $message = "Đã thêm {$addedCount} võ sinh vào lớp!";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} võ sinh đã có trong lớp)";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $class->students()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove student from class
     */
    public function removeStudent(Request $request, $classId, $studentId)
    {
        $class = ClassModel::findOrFail($classId);
        $class->students()->detach($studentId);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa võ sinh khỏi lớp thành công!',
                'count' => $class->students()->count()
            ]);
        }

        return back()->with('success', 'Xóa võ sinh khỏi lớp thành công!');
    }

    /**
     * Show available students to add
     */
    public function showAvailableStudents($id)
    {
        $class = ClassModel::findOrFail($id);

        // Get students not in this class
        $availableStudents = Student::active()
            ->whereNotIn('id', $class->students()->pluck('students.id'))
            ->get();

        return response()->json($availableStudents);
    }
}
