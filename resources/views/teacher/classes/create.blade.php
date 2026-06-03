@extends('layouts.app')

@section('title', 'Create Class')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Create New Class</h1>

    <form action="{{ route('teacher.classes.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium mb-2">Class Name *</label>
            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('name') }}">
            @error('name')
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
                <label for="subject" class="block text-sm font-medium mb-2">Subject</label>
                <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('subject') }}">
            </div>

            <div>
                <label for="section" class="block text-sm font-medium mb-2">Section</label>
                <input type="text" id="section" name="section" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('section') }}">
            </div>

            <div>
                <label for="room" class="block text-sm font-medium mb-2">Room</label>
                <input type="text" id="room" name="room" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('room') }}">
            </div>
        </div>

        <div class="mt-8 flex space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Create Class
            </button>
            <a href="{{ route('teacher.classes.index') }}" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
