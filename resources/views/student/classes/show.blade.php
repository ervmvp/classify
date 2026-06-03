@extends('layouts.app')

@section('title', $classroom->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $classroom->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Teacher: {{ $classroom->teacher->name }}</p>
            @if($classroom->announcement)
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <p class="text-sm"><strong>📢 Announcement:</strong> {{ $classroom->announcement }}</p>
                </div>
            @endif
        </div>
        <form action="{{ route('student.classes.leave', $classroom) }}" method="POST" onsubmit="return confirm('Leave this class?')">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Leave Class</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Subject</p>
            <p class="text-xl font-semibold">{{ $classroom->subject ?? 'N/A' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Assignments</p>
            <p class="text-2xl font-bold">{{ $classroom->assignments()->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Class Students</p>
            <p class="text-2xl font-bold">{{ $classroom->students()->count() }}</p>
        </div>
    </div>

    {{-- Assignments List --}}
    <h2 class="text-2xl font-bold mb-4">Assignments</h2>

    @if($assignments->isEmpty())
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400">No assignments yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($assignments as $assignment)
                @php
                    $submission = auth()->user()->submissions()
                        ->where('assignment_id', $assignment->id)
                        ->first();
                @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">{{ $assignment->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ Str::limit($assignment->description, 200) }}</p>
                        </div>
                        <span class="ml-4 px-3 py-1 rounded-full text-sm font-semibold
                            @if($submission && $submission->status === 'graded') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($submission && $submission->status === 'submitted') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                            @elseif($assignment->isOverdue()) bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                            @endif">
                            @if($submission && $submission->status === 'graded') Graded
                            @elseif($submission && $submission->status === 'submitted') Submitted
                            @elseif($assignment->isOverdue()) Overdue
                            @else Not Submitted
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Total Points</p>
                            <p class="font-semibold">{{ $assignment->total_points }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">Due Date</p>
                            <p class="font-semibold">{{ $assignment->due_date->format('M d, Y') }}</p>
                        </div>
                        @if($submission && $submission->grade !== null)
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Your Grade</p>
                                <p class="font-semibold text-green-600 dark:text-green-400">{{ $submission->grade }}/{{ $assignment->total_points }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('student.submissions.create', $assignment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            @if($submission && $submission->status === 'submitted')
                                View Submission
                            @else
                                Submit Assignment
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection
