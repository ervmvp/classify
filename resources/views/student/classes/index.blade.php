@extends('layouts.app')

@section('title', 'Join Class')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">My Classes</h1>

    @if(auth()->user()->enrolledClasses()->exists())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach(auth()->user()->enrolledClasses()->paginate(9) as $classroom)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $classroom->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ Str::limit($classroom->description, 100) }}</p>
                    
                    <div class="mb-4">
                        <p class="text-sm"><strong>Subject:</strong> {{ $classroom->subject ?? 'N/A' }}</p>
                        <p class="text-sm"><strong>Teacher:</strong> {{ $classroom->teacher->name }}</p>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('student.classes.show', $classroom) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-center text-sm">
                            View
                        </a>
                        <form action="{{ route('student.classes.leave', $classroom) }}" method="POST" class="flex-1" onsubmit="return confirm('Leave this class?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm">
                                Leave
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center mb-8">
            <p class="text-gray-600 dark:text-gray-400">You're not enrolled in any classes yet.</p>
        </div>
    @endif

    {{-- Join Class Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Join a Class</h2>
        
        <form action="{{ route('student.classes.join-code') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="class_code" class="block text-sm font-medium mb-2">Enter Class Code *</label>
                <div class="flex gap-2">
                    <input type="text" id="class_code" name="class_code" placeholder="e.g., ABC123" required 
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 uppercase"
                           value="{{ old('class_code') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Join
                    </button>
                </div>
                @error('class_code')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </form>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
            <p class="text-gray-600 dark:text-gray-400 mb-4">Or scan a QR code from your teacher.</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ask your teacher for the class code or QR code to join the class.</p>
        </div>
    </div>
</div>
@endsection
