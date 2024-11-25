<style>
    .progress-container {
        width: 100%;
        background-color: #e0e0e0;
        border-radius: 9999px;
        height: 10px;
        overflow: hidden;
    }
    .progress-bar {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.5s ease-in-out;
    }
    .progress-primary {
        background-color: #3b82f6;
    }
    .progress-warning {
        background-color: #f59e0b;
    }
    .progress-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .progress-no-data {
        font-size: 0.875rem;
        color: #6b7280;
    }
</style>

@php
    $progress = $getState();
@endphp

@if ($progress)
    <div class="progress-container ">
        <div class="progress-bar {{ $progress['color'] === 'warning' ? 'progress-warning' : 'progress-primary' }}" style="width: {{ $progress['progress'] }}%"></div>
    </div>
    <div class="progress-text">
        {{ number_format($progress['progress'], 0) }}% complete
    </div>
@else
    <div class="progress-no-data">
        No data available
    </div>
@endif

