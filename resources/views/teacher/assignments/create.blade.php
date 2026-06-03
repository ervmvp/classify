@extends('layouts.app')

@section('title', 'Create Assignment')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Create Assignment</h1>
    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $classroom->name }}</p>

    <form action="{{ route('teacher.assignments.store', $classroom) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        @csrf

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium mb-2">Assignment Title *</label>
            <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('title') }}">
            @error('title')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium mb-2">Description</label>
            <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label for="due_date" class="block text-sm font-medium mb-2">Due Date *</label>
                <input type="datetime-local" id="due_date" name="due_date" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('due_date') }}">
                @error('due_date')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="total_points" class="block text-sm font-medium mb-2">Total Points *</label>
                <input type="number" id="total_points" name="total_points" min="1" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('total_points', 100) }}">
                @error('total_points')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="files" class="block text-sm font-medium mb-2">Attachment Files</label>
            <input type="file" id="files" name="files[]" multiple class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can attach multiple files (max 10MB each)</p>
            @error('files.*')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Create Assignment
            </button>
            <a href="{{ route('teacher.classes.show', $classroom) }}" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
