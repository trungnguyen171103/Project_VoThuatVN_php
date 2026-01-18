<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tuition;
use App\Models\TuitionPayment;
use App\Models\Club;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TuitionController extends Controller
{
    /**
     * Show form to create tuition for a class
     */
    public function create()
    {
        $clubs = Club::all();

        // Fetch unique tuition settings grouped by class
        $existingTuitions = Tuition::with(['classModel.club', 'classModel.coach.user'])
            ->select('class_id', 'amount')
            ->selectRaw('MIN(month) as start_month, MIN(year) as start_year, MAX(month) as end_month, MAX(year) as end_year')
            ->groupBy('class_id', 'amount')
            ->get();

        return view('admin.tuitions.create', compact('clubs', 'existingTuitions'));
    }

    /**
     * Update tuition amount for a class
     */
    public function updateByClass(Request $request, $classId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $tuitions = Tuition::where('class_id', $classId)->get();
        $tuitionIds = $tuitions->pluck('id');

        // Update Tuition records
        Tuition::where('class_id', $classId)->update(['amount' => $request->amount]);

        // Update pending payments
        TuitionPayment::whereIn('tuition_id', $tuitionIds)
            ->where('status', 'pending')
            ->update(['amount' => $request->amount]);

        return back()->with('success', 'Đã cập nhật mức học phí thành công!');
    }

    /**
     * Delete all tuition records for a class
     */
    public function destroyByClass($classId)
    {
        $tuitions = Tuition::where('class_id', $classId)->get();

        if ($tuitions->isEmpty()) {
            return back()->with('error', 'Không tìm thấy dữ liệu học phí cho lớp này!');
        }

        $tuitionIds = $tuitions->pluck('id');

        // Check if any payment is already paid
        $hasPaid = TuitionPayment::whereIn('tuition_id', $tuitionIds)
            ->where('status', 'paid')
            ->exists();

        if ($hasPaid) {
            return back()->with('error', 'Không thể xoá vì đã có học sinh nộp tiền cho lớp này!');
        }

        // Delete payments first
        TuitionPayment::whereIn('tuition_id', $tuitionIds)->delete();

        // Delete tuition records
        Tuition::where('class_id', $classId)->delete();

        return back()->with('success', 'Đã xoá học phí của lớp thành công!');
    }

    /**
     * Get classes for selected club
     */
    public function getClubClasses($clubId)
    {
        $classes = ClassModel::where('club_id', $clubId)
            ->where('status', 'active')
            ->get();

        return response()->json($classes);
    }

    /**
     * Get class details (coach, dates)
     */
    public function getClassDetails($classId)
    {
        $class = ClassModel::with('coach.user')->findOrFail($classId);

        return response()->json([
            'coach_name' => $class->coach ? $class->coach->user->name : 'Chưa có HLV',
            'start_date' => $class->start_date ? \Carbon\Carbon::parse($class->start_date)->format('d/m/Y') : '',
            'end_date' => $class->end_date ? \Carbon\Carbon::parse($class->end_date)->format('d/m/Y') : '',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Get class with dates
        $class = ClassModel::with('students')->findOrFail($request->class_id);

        if (!$class->start_date || !$class->end_date) {
            return back()->withErrors(['class_id' => 'Lớp học chưa có thời gian bắt đầu và kết thúc']);
        }

        // Calculate all months between start_date and end_date
        $startDate = \Carbon\Carbon::parse($class->start_date);
        $endDate = \Carbon\Carbon::parse($class->end_date);

        $months = [];
        $current = $startDate->copy()->startOfMonth();

        while ($current->lte($endDate)) {
            $months[] = [
                'month' => $current->month,
                'year' => $current->year,
                'due_date' => $current->copy()->endOfMonth()->toDateString()
            ];
            $current->addMonth();
        }

        // Create tuition records for each month
        $createdCount = 0;
        foreach ($months as $monthData) {
            // Check if tuition already exists for this class and month
            $existing = Tuition::where('class_id', $class->id)
                ->where('month', $monthData['month'])
                ->where('year', $monthData['year'])
                ->first();

            if ($existing) {
                continue; // Skip if already exists
            }

            $tuition = Tuition::create([
                'class_id' => $class->id,
                'amount' => $request->amount,
                'month' => $monthData['month'],
                'year' => $monthData['year'],
                'due_date' => $monthData['due_date'],
            ]);

            // Create tuition payment records for all students in the class
            foreach ($class->students as $student) {
                TuitionPayment::create([
                    'tuition_id' => $tuition->id,
                    'student_id' => $student->id,
                    'amount' => $request->amount,
                    'status' => 'pending',
                ]);
            }

            $createdCount++;
        }

        return redirect()->route('admin.tuitions.debts')
            ->with('success', "Đã tạo {$createdCount} kỳ học phí thành công!");
    }

    /**
     * Display debt list
     */
    public function debtList(Request $request)
    {
        $clubs = Club::all();

        // If class is selected, show all students in that class (but only for months that are due)
        if ($request->class_id) {
            $query = TuitionPayment::with('student', 'tuition.classModel.club')
                ->whereHas('tuition', function ($q) use ($request) {
                    $q->where('class_id', $request->class_id)
                        ->where('due_date', '<=', now()->toDateString()); // Only show if due date has passed
                });
        } else {
            // Otherwise show only unpaid/overdue (and only for months that are due)
            $query = TuitionPayment::with('student', 'tuition.classModel.club')
                ->whereIn('status', ['pending', 'overdue'])
                ->whereHas('tuition', function ($q) {
                    $q->where('due_date', '<=', now()->toDateString()); // Only show if due date has passed
                });

            // Filter by club if selected
            if ($request->club_id) {
                $query->whereHas('tuition.classModel', function ($q) use ($request) {
                    $q->where('club_id', $request->club_id);
                });
            }
        }

        $debts = $query->orderByRaw("CASE WHEN status = 'pending' THEN 1 WHEN status = 'overdue' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tuitions.debts', compact('debts', 'clubs'));
    }

    /**
     * Mark payment as paid
     */
    public function markPaid($id)
    {
        $payment = TuitionPayment::findOrFail($id);
        $payment->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);

        return back()->with('success', 'Đã đánh dấu thanh toán thành công!');
    }

    /**
     * Print bill
     */
    public function printBill($id)
    {
        $payment = TuitionPayment::with('student', 'tuition.classModel.club')->findOrFail($id);

        return view('admin.tuitions.bill', compact('payment'));
    }
}
