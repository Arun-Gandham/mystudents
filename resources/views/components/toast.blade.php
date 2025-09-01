@props(['type' => 'info', 'title' => 'Notification', 'message' => ''])

@php
    $bgClass = match($type) {
        'success' => 'bg-success text-white',
        'error'   => 'bg-danger text-white',
        'danger'  => 'bg-danger text-white',
        'info'    => 'bg-info text-white',
        default   => 'bg-secondary text-white'
    };
@endphp

<div class="toast custom-toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header {{ $bgClass }}">
        <strong class="me-auto">{{ $title }}</strong>
        <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ $message }}
    </div>
</div>
