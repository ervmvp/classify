@extends('layouts.app')

@section('title', 'Class Students')

@section('content')
<div class="py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $classroom->name }} - Students</h1>
        <a href="{{ route('teacher.classes.show', $classroom) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Back
        </a>
    </div>

    @if($students->isEmpty())
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400">No students enrolled in this class yet.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Joined Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-3 font-medium">
                                @if($student->profile_picture)
                                    <img src="{{ Storage::url($student->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full inline-block me-2">
                                @endif
                                {{ $student->name }}
                            </td>
                            <td class="px-6 py-3">{{ $student->email }}</td>
                            <td class="px-6 py-3">{{ $student->pivot->joined_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <form action="{{ route('teacher.classes.remove-student', $classroom) }}" method="POST" class="inline" onsubmit="return confirm('Remove this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
