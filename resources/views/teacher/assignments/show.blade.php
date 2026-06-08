@extends('layouts.app')

@section('title', 'Grade Submissions')

@section('content')
{{-- Assignment Files --}}
@if($assignment->files->count())
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-semibold mb-3">Assignment Files</h2>
    <ul class="space-y-2">
        @foreach($assignment->files as $file)
            <li class="flex items-center gap-3">
                <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                   class="text-blue-600 hover:underline dark:text-blue-400">
                    {{ $file->file_name }}
                </a>
                <span class="text-xs text-gray-500">({{ number_format($file->file_size / 1024, 1) }} KB)</span>
            </li>
        @endforeach
    </ul>
</div>
@endif
<div class="py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">{{ $assignment->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $assignment->classroom->name }}</p>
        </div>
        <a href="{{ route('teacher.classes.show', $assignment->classroom) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Back to Class
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">Student</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Submitted</th>
                    <th class="px-6 py-3 text-left">Grade</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-6 py-3 font-medium">
                            @if($submission->student->profile_picture)
                                <img src="{{ Storage::url($submission->student->profile_picture) }}" alt="Profile" class="w-6 h-6 rounded-full inline-block me-2">
                            @endif
                            {{ $submission->student->name }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-sm font-semibold
                                @if($submission->status === 'graded') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($submission->status === 'submitted') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                @endif">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @if($submission->submitted_at)
                                {{ $submission->submitted_at->format('M d h:i A') }}
                                @if($submission->isLate())
                                    <p class="text-xs text-red-600 dark:text-red-400">Late</p>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($submission->grade !== null)
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $submission->grade }}/{{ $assignment->total_points }}</span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">Not graded</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('teacher.submissions.show', $submission) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-600 dark:text-gray-400">
                            No submissions yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
