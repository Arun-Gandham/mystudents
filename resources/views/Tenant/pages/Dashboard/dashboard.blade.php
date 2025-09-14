@extends('tenant.layouts.layout1')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- Student Attendance Widget --}}
        @can('dashboard:students')
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Students</h5>
                    <p>Present Today: <strong>{{ $data['students']['presentToday'] ?? 0 }}</strong></p>
                    <p>Total: <strong>{{ $data['students']['total'] ?? 0 }}</strong></p>
                    <a href="#" class="btn btn-sm btn-primary">View All Students</a>
                </div>
            </div>
        </div>
        @endcan

        {{-- Staff Attendance Widget --}}
        @can('dashboard:staff')
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Staff</h5>
                    <p>Present Today: <strong>{{ $data['staff']['presentToday'] ?? 0 }}</strong></p>
                    <p>Total: <strong>{{ $data['staff']['total'] ?? 0 }}</strong></p>
                    <a href="#" class="btn btn-sm btn-primary">View All Staff</a>
                </div>
            </div>
        </div>
        @endcan

        {{-- Next Holiday Widget --}}
        @can('dashboard:holidays')
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Next Holiday</h5>
                    @if($data['holiday'])
                        <p>{{ $data['holiday']->name }} ({{ $data['holiday']->date->format('d M Y') }})</p>
                    @else
                        <p>No upcoming holidays</p>
                    @endif
                    <a href="#" class="btn btn-sm btn-primary">View All Holidays</a>
                </div>
            </div>
        </div>
        @endcan

        {{-- Fees Collection --}}
        @can('dashboard:fees')
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Fees Collection</h5>
                    <p>Collected: ₹{{ number_format($data['fees']['collected'] ?? 0) }}</p>
                    <p>Target: ₹{{ number_format($data['fees']['target'] ?? 0) }}</p>
                    <a href="#" class="btn btn-sm btn-primary">Go to Fees</a>
                </div>
            </div>
        </div>
        @endcan

    </div>

    {{-- Example Graph (Students Attendance Days Wise) --}}
    @can('dashboard:students')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Student Attendance (Last 7 Days)</h5>
                    <canvas id="studentAttendanceGraph"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('studentAttendanceGraph');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_map(fn($d) => $d->format('d M'), collect(range(0,6))->map(fn($i)=> now()->subDays($i))->reverse()->toArray())),
            datasets: [{
                label: 'Attendance %',
                data: [85, 90, 92, 87, 95, 93, 88], // Replace with backend data
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
            }]
        }
    });
}
</script>
@endpush
