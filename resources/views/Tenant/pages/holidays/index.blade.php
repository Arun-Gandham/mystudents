@extends('tenant.baselayout')

@section('title', 'School Holiday Calendar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <h2>School Holiday Calendar</h2>
    <div id="calendar"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        events: '{{ tenant_route("tenant.school_holidays.calendar") }}', // fetches from your JSON API
    });
    calendar.render();
});
</script>
@endpush
