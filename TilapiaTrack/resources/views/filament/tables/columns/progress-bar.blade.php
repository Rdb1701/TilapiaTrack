@php
    $progress = $getState();
@endphp

@if ($progress)
    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div class="h-2.5 rounded-full {{ $progress['color'] === 'warning' ? 'bg-yellow-400' : 'bg-blue-600' }}" style="width: {{ $progress['progress'] }}%"></div>
    </div>
    <div class="text-sm mt-1">
        {{ number_format($progress['progress'], 0) }}% complete
    </div>
@else
    <div class="text-sm text-gray-500">
        No data available
    </div>
@endif

