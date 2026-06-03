@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Audit Trail</h1>
        <a href="{{ route('admin.audit-logs.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            📥 Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="action" placeholder="Filter by action" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ request('action') }}">
            <input type="text" name="entity_type" placeholder="Filter by entity type" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ request('entity_type') }}">
            <input type="date" name="date_from" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700" value="{{ request('date_from') }}">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Search</button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">User</th>
                    <th class="px-6 py-3 text-left">Action</th>
                    <th class="px-6 py-3 text-left">Entity</th>
                    <th class="px-6 py-3 text-left">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-3">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-sm font-semibold
                                @if($log->action === 'created') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($log->action === 'deleted') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                @endif">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm">{{ $log->entity_type }}</td>
                        <td class="px-6 py-3 text-sm">{{ $log->ip_address ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-600 dark:text-gray-400">
                            No logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
