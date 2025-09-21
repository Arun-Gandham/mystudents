@extends('tenant.layouts.layout1')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- Students --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Students</h5>
                    <p>Present Today: <strong>{{ $data['students']['presentToday'] ?? 0 }}</strong></p>
                    <p>Total: <strong>{{ $data['students']['total'] ?? 0 }}</strong></p>
                    <a href="{{ tenant_route('tenant.students.index') }}" class="btn btn-sm btn-primary">View All Students</a>
                </div>
            </div>
        </div>

        {{-- Staff --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Staff</h5>
                    <p>Present Today: <strong>{{ $data['staff']['presentToday'] ?? 0 }}</strong></p>
                    <p>Total: <strong>{{ $data['staff']['total'] ?? 0 }}</strong></p>
                    <a href="{{ tenant_route('tenant.staff.index') }}" class="btn btn-sm btn-primary">View All Staff</a>
                </div>
            </div>
        </div>

        {{-- Next Holiday --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Next Holiday</h5>
                    @if(!empty($data['holiday']))
                        <p>{{ $data['holiday']->name }} ({{ $data['holiday']->date->format('d M Y') }})</p>
                    @else
                        <p>No upcoming holidays</p>
                    @endif
                    <a href="{{ tenant_route('tenant.school_holidays.index') }}" class="btn btn-sm btn-primary">View All Holidays</a>
                </div>
            </div>
        </div>

        {{-- Fees Collection --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Fees Collection</h5>
                    <p>Collected: ₹{{ number_format($data['fees']['collected'] ?? 0) }}</p>
                    <p>Target: ₹{{ number_format($data['fees']['target'] ?? 0) }}</p>
                    <small class="text-muted">Receipts: {{ $data['fees']['receiptsCount'] ?? 0 }}</small><br>
                    <a href="{{ tenant_route('tenant.fees.fee-receipts.all') }}" class="btn btn-sm btn-primary mt-1">Go to Fees</a>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        {{-- Academics --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Academics</h5>
                    <p>Current Year: <strong>{{ optional($data['academics']['currentYear'])->name ?? '—' }}</strong></p>
                    <p>Grades: <strong>{{ $data['academics']['grades'] ?? 0 }}</strong> | Sections: <strong>{{ $data['academics']['sections'] ?? 0 }}</strong></p>
                    <a href="{{ tenant_route('tenant.academic_years.index') }}" class="btn btn-sm btn-primary">Manage</a>
                </div>
            </div>
        </div>

        {{-- Applications --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Applications</h5>
                    <p>Total: <strong>{{ $data['applications']['total'] ?? 0 }}</strong></p>
                    @php $by = $data['applications']['byStatus'] ?? collect(); @endphp
                    <p class="mb-2" style="font-size: 0.9rem;">
                        @foreach(($by instanceof \Illuminate\Support\Collection ? $by : collect($by)) as $st => $cnt)
                            <span class="badge text-bg-light me-1">{{ ucfirst($st ?? 'unknown') }}: {{ $cnt }}</span>
                        @endforeach
                    </p>
                    <a href="{{ tenant_route('tenant.applications.index') }}" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>

        {{-- Admissions --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Admissions</h5>
                    <p>Total: <strong>{{ $data['admissions']['total'] ?? 0 }}</strong></p>
                    <p>Today: <strong>{{ $data['admissions']['today'] ?? 0 }}</strong></p>
                    <a href="{{ tenant_route('tenant.admissions.index') }}" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>

        {{-- Upcoming Exams --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Upcoming Exams</h5>
                    @if(!empty($data['exams']) && count($data['exams']))
                        <ul class="list-unstyled mb-2" style="max-height: 120px; overflow:auto;">
                            @foreach($data['exams'] as $exam)
                                <li>
                                    <strong>{{ $exam->name }}</strong>
                                    <small class="text-muted"> ({{ optional($exam->starts_on)->format('d M') }} - {{ optional($exam->ends_on)->format('d M') }})</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No upcoming exams</p>
                    @endif
                    <a href="{{ tenant_route('tenant.exams.index') }}" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Example Graph (Students Attendance Days Wise) --}}
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
                data: [85, 90, 92, 87, 95, 93, 88], // TODO: wire with live data
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
            }]
        }
    });
}
</script>
@endpush

