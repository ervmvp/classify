@extends('layouts.app')

@section('title', 'Teacher Classes')

@section('content')
<div class="py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">My Classes</h1>
        <a href="{{ route('teacher.classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Create Class
        </a>
    </div>

    @if($classes->isEmpty())
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400">You haven't created any classes yet.</p>
            <a href="{{ route('teacher.classes.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Create Your First Class
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $classroom)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $classroom->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ Str::limit($classroom->description, 100) }}</p>
                    
                    <div class="mb-4">
                        <p class="text-sm"><strong>Subject:</strong> {{ $classroom->subject ?? 'N/A' }}</p>
                        <p class="text-sm"><strong>Code:</strong> <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $classroom->class_code }}</code></p>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('teacher.classes.show', $classroom) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-center text-sm">
                            View
                        </a>
                        <a href="{{ route('teacher.classes.edit', $classroom) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-center text-sm">
                            Edit
                        </a>
                        <form action="{{ route('teacher.classes.destroy', $classroom) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
