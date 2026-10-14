@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')

<div class="header-card">
    <p class="eyebrow">STUDENT MANAGEMENT</p>
    <h1 class="page-title">Manage student records and information</h1>
    <p class="page-subtitle">Keep enrollment, scores, and status tidy in one place.</p>

    <div class="header-actions">
        <a href="{{ route('students.create') }}" class="btn btn-primary">+ Add Student</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Students</p>
        <p class="stat-sublabel">All records</p>
        <p class="stat-value">{{ $stats['total'] ?? $students->total() }}</p>
    </div>

<div class="stat-card">
    <p class="stat-label">Failed Students</p>
    <p class="stat-sublabel">Score &lt; 50</p>
    <p class="stat-value">
        {{ $stats['failed'] ?? 0 }}
        <span class="pill pill-red">{{ $stats['failed_rate'] ?? 0 }}%</span>
    </p>
</div>

    <div class="stat-card">
        <p class="stat-label">Top Scorer</p>
        <p class="stat-sublabel">{{ $stats['top_name'] ?? '—' }}</p>
        <p class="stat-value">{{ $stats['top_score'] ?? 0 }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Pass Rate</p>
        <p class="stat-sublabel">Score ≥ 50</p>
        <p class="stat-value">{{ number_format($stats['pass_rate'] ?? 0, 1) }}%</p>
    </div>
</div>

<div class="table-card">
    <div class="table-header">
        <div>
            <h2 class="table-title">All Students</h2>
            <p class="table-subtitle">{{ $students->total() }} students found</p>
        </div>
    </div>

    <form method="GET" action="{{ route('students.index') }}" class="filters-row">
        <div class="filters-left">
            <button type="button" class="btn btn-chip" onclick="window.print()">🖨 Print</button>
        </div>

        <div class="filters-right">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name, student ID"
                    value="{{ request('search') }}"
                >
            </div>

            <select name="department" class="select-input" onchange="this.form.submit()">
                <option value="">Select Department</option>
                @foreach($departments ?? [] as $dept)
                    <option value="{{ $dept }}" @selected(request('department') == $dept)>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>

            <select name="grade" class="select-input" onchange="this.form.submit()">
                <option value="">Select Grade</option>
                @foreach(['A+','A','A-','B+','B','B-','C+','C','D','F'] as $g)
                    <option value="{{ $g }}" @selected(request('grade') == $g)>{{ $g }}</option>
                @endforeach
            </select>

            <select name="sort" class="select-input" onchange="this.form.submit()">
                <option value="">Sort By</option>
                <option value="name_asc"    @selected(request('sort') == 'name_asc')>Name (A–Z)</option>
                <option value="name_desc"   @selected(request('sort') == 'name_desc')>Name (Z–A)</option>
                <option value="score_desc"  @selected(request('sort') == 'score_desc')>Score (High–Low)</option>
                <option value="score_asc"   @selected(request('sort') == 'score_asc')>Score (Low–High)</option>
                <option value="id_asc"      @selected(request('sort') == 'id_asc')>Student ID (Asc)</option>
                <option value="id_desc"     @selected(request('sort') == 'id_desc')>Student ID (Desc)</option>
            </select>

            <button type="submit" class="btn btn-primary">Apply</button>

            @if(request()->hasAny(['search','department','grade','sort']))
                <a href="{{ route('students.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="students-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Points</th>
                    <th>Remarks</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><span class="mono">{{ $student->student_id }}</span></td>
                        <td>
                            <div class="student-cell">
                                <div class="avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                                <span>{{ $student->name }}</span>
                            </div>
                        </td>
                        <td>{{ $student->department }}</td>
                        <td><strong>{{ $student->score }}</strong></td>
                        <td>
                            <span class="badge {{ $student->grade === 'F' ? 'badge-red' : ($student->grade === 'D' ? 'badge-yellow' : 'badge-green') }}">
                                {{ $student->grade }}
                            </span>
                        </td>
                        <td>{{ number_format($student->grade_points, 2) }}</td>
                        <td><span class="remarks">{{ $student->remarks }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline">Edit</a>

                            <form action="{{ route('students.destroy', $student->id) }}"
                                  method="POST"
                                  class="inline-form"
                                  onsubmit="return confirm('Delete {{ $student->name }}? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <p>No students found.</p>
                            @if(request()->hasAny(['search','department','grade']))
                                <a href="{{ route('students.index') }}">Clear filters</a>
                            @else
                                <a href="{{ route('students.create') }}">Add your first student →</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div class="pagination-wrapper">
            {{ $students->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
