@extends('layouts.app')

@section('title', $classroom->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $classroom->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $classroom->description }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('teacher.classes.edit', $classroom) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Edit</a>
            <a href="{{ route('teacher.classes.students', $classroom) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Students</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Class Code</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $classroom->class_code }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Subject</p>
            <p class="text-xl font-semibold">{{ $classroom->subject ?? 'N/A' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Students</p>
            <p class="text-2xl font-bold">{{ $classroom->students()->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Assignments</p>
            <p class="text-2xl font-bold">{{ $classroom->assignments()->count() }}</p>
        </div>
    </div>

    {{-- QR Code Display --}}
    @if($classroom->qr_code_path)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Class QR Code</h3>
            <img src="{{ Storage::url($classroom->qr_code_path) }}" alt="QR Code" class="w-48 h-48">
        </div>
    @endif

    {{-- Create Assignment --}}
    <div class="mb-8">
        <a href="{{ route('teacher.assignments.create', ['classroom' => $classroom]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded inline-block">
            + Create Assignment
        </a>
    </div>

    {{-- Assignments List --}}
    <h2 class="text-2xl font-bold mb-4">Assignments</h2>

    @if($assignments->isEmpty())
        <p class="text-gray-600 dark:text-gray-400">No assignments yet.</p>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Due Date</th>
                        <th class="px-6 py-3 text-left">Submissions</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-3">{{ $assignment->title }}</td>
                            <td class="px-6 py-3">
                                {{ $assignment->due_date->format('M d, Y h:i A') }}
                                @if($assignment->isOverdue())
                                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs px-2 py-1 rounded">Overdue</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                {{ $assignment->submissions()->count() }} / {{ $classroom->students()->count() }}
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('teacher.assignments.show', $assignment) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">View</a>
                                <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 ms-2">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection
