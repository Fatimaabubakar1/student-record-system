@extends('layouts.app')

@section('title', 'Add Student')

@section('content')

<div class="form-page">
    <div class="form-card">

        <div class="form-header">
            <p class="eyebrow">NEW RECORD</p>
            <h1 class="page-title">Add a student</h1>
            <p class="page-subtitle">Fill in the details below to add a new student record.</p>
        </div>

        <form action="{{ route('students.store') }}" method="POST" class="student-form">
            @csrf

            <div class="form-group">
                <label for="student_id">Student ID <span class="required">*</span></label>
                <input
                    type="text"
                    id="student_id"
                    name="student_id"
                    value="{{ old('student_id') }}"
                    placeholder="e.g. STU-2026-001"
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
                    value="{{ old('name') }}"
                    placeholder="e.g. Esther Ali"
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
                        <option value="{{ $dept }}" @selected(old('department') == $dept)>{{ $dept }}</option>
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
                    value="{{ old('score') }}"
                    placeholder="e.g. 85"
                    class="@error('score') input-error @enderror"
                    required
                >
                @error('score')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('students.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">+ Add Student</button>
            </div>
        </form>

    </div>
</div>

@endsection
