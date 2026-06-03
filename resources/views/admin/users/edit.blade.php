@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium mb-2">Name *</label>
            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('name', $user->name) }}">
            @error('name')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium mb-2">Email *</label>
            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ old('email', $user->email) }}">
            @error('email')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="role" class="block text-sm font-medium mb-2">Role *</label>
            <select id="role" name="role" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                <option value="teacher" @selected($user->role === 'teacher')>Teacher</option>
                <option value="student" @selected($user->role === 'student')>Student</option>
            </select>
            @error('role')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
