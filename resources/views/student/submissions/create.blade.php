@extends('layouts.app')

@section('title', 'Submit Assignment')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="md:col-span-2">
            <h1 class="text-3xl font-bold mb-2">{{ $assignment->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $assignment->classroom->name }}</p>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <p class="mb-4">{{ $assignment->description }}</p>

                @if($assignment->files()->exists())
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Attachments:</h3>
                        <ul class="space-y-2">
                            @foreach($assignment->files() as $file)
                                <li>
                                    <a href="{{ Storage::url($file->file_path) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center">
                                        📎 {{ $file->file_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Submission Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">
                    @if($submission->status === 'submitted' || $submission->status === 'graded')
                        Your Submission
                    @else
                        Submit Assignment
                    @endif
                </h2>

                <form action="{{ route('student.submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium mb-2">Your Answer</label>
                        <textarea id="content" name="content" rows="6" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                                  @if($submission->status === 'submitted' || $submission->status === 'graded') readonly @endif>{{ old('content', $submission->content) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="files" class="block text-sm font-medium mb-2">Upload Files</label>
                        <input type="file" id="files" name="files[]" multiple class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                               @if($submission->status === 'submitted' || $submission->status === 'graded') disabled @endif>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Accepted: PDF, DOCX, PPTX, JPG, PNG, ZIP (max 10MB each)</p>
                    </div>

                    @if($submission->files()->exists())
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Your Files:</h3>
                            <ul class="space-y-2">
                                @foreach($submission->files() as $file)
                                    <li class="flex justify-between items-center">
                                        <a href="{{ Storage::url($file->file_path) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            📎 {{ $file->file_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($submission->status !== 'submitted' && $submission->status !== 'graded')
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Submit Assignment
                        </button>
                    @endif
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-fit">
            <h3 class="font-semibold mb-4">Assignment Details</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Due Date</p>
                    <p class="font-semibold">{{ $assignment->due_date->format('M d, Y h:i A') }}</p>
                    @if($assignment->isOverdue())
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">📌 Overdue</p>
                    @elseif(now()->diffInHours($assignment->due_date) < 24)
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">⏰ Due soon</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Points</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $assignment->total_points }}</p>
                </div>

                @if($submission->status === 'graded')
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Your Grade</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $submission->grade }}/{{ $assignment->total_points }}</p>
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        @if($submission->status === 'graded') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($submission->status === 'submitted') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Comments from Teacher</h2>
        
        @forelse($submission->comments()->where('user_id', '!=', auth()->id())->get() as $comment)
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
    </div>
</div>
@endsection
