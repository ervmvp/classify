@extends('layouts.app')

@section('title', 'Grade Submission')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">{{ $submission->assignment->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Student: {{ $submission->student->name }}</p>
            </div>

            {{-- Student Content --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Submission</h2>
                
                @if($submission->content)
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        {{ $submission->content }}
                    </div>
                @endif

                @if($submission->files->count())
                    <div class="mb-6">
                        <h3 class="font-semibold mb-3">Student Files:</h3>
                        <ul class="space-y-2">
                            @foreach($submission->files as $file)
                                <li>
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center">
                                        📎 {{ $file->file_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Grading Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Grade Submission</h2>

                <form action="{{ route('teacher.submissions.grade', $submission) }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="grade" class="block text-sm font-medium mb-2">Grade (out of {{ $submission->assignment->total_points }})</label>
                        <input type="number" id="grade" name="grade" min="0" max="{{ $submission->assignment->total_points }}" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                               value="{{ old('grade', $submission->grade) }}">
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        Save Grade
                    </button>
                </form>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Comments</h2>

                @forelse($comments as $comment)
                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-semibold">{{ $comment->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                        <p>{{ $comment->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-400">No comments yet.</p>
                @endforelse

                <form action="{{ route('teacher.submissions.comment', $submission) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    @csrf

                    <textarea name="comment" placeholder="Add a comment for the student..." rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 mb-2"></textarea>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Add Comment
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-fit">
            <h3 class="font-semibold mb-4">Submission Info</h3>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Assignment Due</p>
                    <p class="font-semibold">{{ $submission->assignment->due_date->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Submitted</p>
                    <p class="font-semibold">
                        @if($submission->submitted_at)
                            {{ $submission->submitted_at->format('M d, Y h:i A') }}
                            @if($submission->isLate())
                                <p class="text-xs text-red-600 dark:text-red-400">⚠️ Late</p>
                            @endif
                        @else
                            Not submitted
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Current Grade</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        @if($submission->grade !== null)
                            {{ $submission->grade }}/{{ $submission->assignment->total_points }}
                        @else
                            Not graded
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('teacher.assignments.show', $submission->assignment) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        ← Back to Assignment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
