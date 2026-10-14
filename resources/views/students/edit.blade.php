@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')

<div class="form-page">
    <div class="form-card">

        <div class="form-header">
            <p class="eyebrow">UPDATE RECORD</p>
            <h1 class="page-title">Edit student</h1>
            <p class="page-subtitle">
                Updating <strong>{{ $student->name }}</strong> ({{ $student->student_id }})
            </p>
        </div>

        <form action="{{ route('students.update', $student->id) }}" method="POST" class="student-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="student_id">Student ID <span class="required">*</span></label>
                <input
                    type="text"
                    id="student_id"
                    name="student_id"
                    value="{{ old('student_id', $student->student_id) }}"
                    class="@error('student_id') input-error @enderror"
                    required
                >
                @error('student_id')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $student->name) }}"
                    class="@error('name') input-error @enderror"
                    required
                >
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="department">Department <span class="required">*</span></label>
                <select
                    id="department"
                    name="department"
                    class="@error('department') input-error @enderror"
                    required
                >
                    <option value="">— Select Department —</option>
                    @foreach(['Computer Science','Software Engineering','Cyber Security','Information Technology','Mathematics','Physics'] as $dept)
                        <option value="{{ $dept }}" @selected(old('department', $student->department) == $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
                @error('department')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="score">Score (0–100) <span class="required">*</span></label>
                <input
                    type="number"
                    id="score"
                    name="score"
                    min="0"
                    max="100"
                    value="{{ old('score', $student->score) }}"
                    class="@error('score') input-error @enderror"
                    required
                >
                @error('score')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="info-box">
                Current grade: <strong>{{ $student->grade }}</strong>
                ({{ number_format($student->grade_points, 2) }} points) —
                {{ $student->remarks }}
            </div>

            <div class="form-actions">
                <a href="{{ route('students.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>

    </div>
</div>

@endsection
