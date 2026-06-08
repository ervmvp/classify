<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Submission Details
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">

        {{-- Submission Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Your Submission</h3>

            <div class="mb-3">
                <span class="font-medium">Status:</span>
                <span class="capitalize">{{ $submission->status }}</span>
            </div>

            <div class="mb-3">
                <span class="font-medium">Submitted At:</span>
                {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}
            </div>

            <div class="mb-3">
                <span class="font-medium">Grade:</span>
                {{ $submission->grade ?? 'Not graded yet' }}
            </div>

            <div class="mb-3">
                <span class="font-medium">Content:</span>
                <p class="mt-1">{{ $submission->content }}</p>
            </div>
        </div>
        
        {{-- Comments --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Comments</h3>

            @forelse ($comments as $comment)
                <div class="mb-4 border-b pb-4">
                    <p class="text-sm font-medium">{{ $comment->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y h:i A') }}</p>
                    <p class="mt-1">{{ $comment->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500">No comments yet.</p>
            @endforelse
        </div>

        {{-- Add Comment --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Add a Comment</h3>
            <form action="{{ route('student.submissions.comment', $submission) }}" method="POST">
                @csrf
                <textarea name="comment" rows="3" class="w-full border rounded p-2 dark:bg-gray-700" placeholder="Write a comment...">{{ old('comment') }}</textarea>
                @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Submit Comment
                </button>
            </form>
        </div>

    </div>
</x-app-layout>