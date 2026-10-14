<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
        public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('grade')) {
            $range = match ($request->grade) {
                'A+' => [91, 100], 'A'  => [86, 90], 'A-' => [81, 85],
                'B+' => [76, 80],  'B'  => [71, 75], 'B-' => [66, 70],
                'C+' => [61, 65],  'C'  => [50, 60], 'D'  => [45, 49],
                'F'  => [0, 44],
                default => null,
            };
            if ($range) {
                $query->whereBetween('score', $range);
            }
        }

        switch ($request->input('sort')) {
            case 'name_asc':   $query->orderBy('name', 'asc'); break;
            case 'name_desc':  $query->orderBy('name', 'desc'); break;
            case 'score_desc': $query->orderBy('score', 'desc'); break;
            case 'score_asc':  $query->orderBy('score', 'asc'); break;
            case 'id_asc':     $query->orderBy('student_id', 'asc'); break;
            case 'id_desc':    $query->orderBy('student_id', 'desc'); break;
            default:           $query->latest();
        }

        $students = $query->paginate(15)->withQueryString();

        $totalStudents = Student::count();
        $failedCount   = Student::where('score', '<', 50)->count();

        $stats = [
            'total'     => $totalStudents,
            'failed'      => $failedCount,
            'failed_rate' => $totalStudents > 0
                ? round(($failedCount / $totalStudents) * 100, 1)
                : 0, 
            'top_name'  => Student::orderByDesc('score')->value('name') ?? '—',
            'top_score' => Student::max('score') ?? 0,
            'pass_rate' => $totalStudents > 0
                ? round((Student::where('score', '>=', 50)->count() / $totalStudents) * 100, 1)
                : 0,
        ];

        $departments = Student::distinct()->orderBy('department')->pluck('department');

        return view('students.index', compact('students', 'stats', 'departments'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required', 'string', 'max:50', 'unique:students,student_id'
            ],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'score' => ['required', 'integer', 'between:0,100'],
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_id')
                    ->ignore($student->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'score' => ['required', 'integer', 'between:0,100'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
