@extends('tenant.baselayout')

@section('title', 'Copy Timetable From Previous')

@section('content')
<div class="container">
    <h2>Copy Timetable From Previous</h2>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.timetables.copySave', ['school_sub' => current_school_sub()]) }}">
        @csrf

        <div class="mb-3">
            <label>Select Timetable to Copy From</label>
            <select id="source_timetable" name="source_timetable" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($allTimetables as $tt)
                    <option value="{{ $tt->id }}" {{ old('source_timetable') == $tt->id ? 'selected' : '' }}>
                        {{ $tt->section->grade->name }} - {{ $tt->section->name }} ({{ $tt->title }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
        <div class="col">
            <label>Section</label>
            <select name="section_id" class="form-control" required>
                <option value="">-- Select Section --</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->grade->name }} - {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <label>Day</label>
            <select name="day" class="form-control" required>
                <option value="">-- Select Day --</option>
                @foreach(\App\Constants\WeekDays::LIST as $key => $val)
                    <option value="{{ $key }}" {{ old('day') == $key ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
        <div class="row mb-3">
            <div class="col">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="col">
                <label>Effective From</label>
                <input type="date" name="effective_from" class="form-control" value="{{ old('effective_from') }}" required>
            </div>
            <div class="col">
                <label>Effective To</label>
                <input type="date" name="effective_to" class="form-control" value="{{ old('effective_to') }}">
            </div>
        </div>

        <h5>Periods</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Period No</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Room</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="periodRows">
    {{-- Render old periods ONLY if validation failed --}}
    @if(old('periods'))
        @foreach(old('periods') as $i => $p)
            <tr>
                <td><input type="number" name="periods[{{ $i }}][period_no]" class="form-control"
                           value="{{ $p['period_no'] ?? '' }}" required></td>
                <td>
                    <input type="time" name="periods[{{ $i }}][starts_at]" class="form-control"
                           value="{{ $p['starts_at'] ?? '' }}" required>
                    -
                    <input type="time" name="periods[{{ $i }}][ends_at]" class="form-control"
                           value="{{ $p['ends_at'] ?? '' }}" required>
                </td>
                <td>
                    <select name="periods[{{ $i }}][subject_id]" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ ($p['subject_id'] ?? '') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="periods[{{ $i }}][teacher_id]" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ ($p['teacher_id'] ?? '') == $t->id ? 'selected' : '' }}>
                                {{ $t->full_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="periods[{{ $i }}][room]" class="form-control" value="{{ $p['room'] ?? '' }}"></td>
                <td><input type="text" name="periods[{{ $i }}][note]" class="form-control" value="{{ $p['note'] ?? '' }}"></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">X</button></td>
            </tr>
        @endforeach
    @endif
</tbody>

        </table>

        <button type="button" id="btnAddPeriod" class="btn btn-secondary">+ Add Period</button>
        <button type="submit" class="btn btn-success mt-2">Save Timetable</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
/**
 * Add a new row for a period
 */
window.addRow = function(period = {}, index = null) {
    // Skip if no essential fields (avoid empty rows)
    if (!period.period_no && !period.starts_at && !period.ends_at) {
        return;
    }

    let tbody = document.getElementById('periodRows');
    if (index === null) index = tbody.querySelectorAll('tr').length;

    let row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="number" name="periods[${index}][period_no]" class="form-control"
                   value="${period.period_no || ''}" required></td>
        <td>
            <input type="time" name="periods[${index}][starts_at]" class="form-control"
                   value="${period.starts_at || ''}" required>
            -
            <input type="time" name="periods[${index}][ends_at]" class="form-control"
                   value="${period.ends_at || ''}" required>
        </td>
        <td>
            <select name="periods[${index}][subject_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" ${period.subject_id == '{{ $s->id }}' ? 'selected' : ''}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="periods[${index}][teacher_id]" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" ${period.teacher_id == '{{ $t->id }}' ? 'selected' : ''}>
                        {{ $t->full_name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="periods[${index}][room]" class="form-control" value="${period.room || ''}"></td>
        <td><input type="text" name="periods[${index}][note]" class="form-control" value="${period.note || ''}"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">X</button></td>
    `;
    tbody.appendChild(row);
};

window.loadPeriods = function(timetableId) {
    if (!timetableId) return;

    let url = @json(tenant_route('tenant.timetables.periods.api', [
        'school_sub' => current_school_sub(),
        'timetable' => '__ID__'
    ]));
    url = url.replace('__ID__', timetableId);

    fetch(url)
        .then(res => res.json())
        .then(periods => {
            let tbody = document.getElementById('periodRows');
            tbody.innerHTML = ''; // clear rows before inserting

            periods.forEach((p, i) => addRow(p, i)); // only valid rows added
        })
        .catch(err => console.error("Error fetching periods:", err));
};



/**
 * Attach event listeners
 */
document.addEventListener("DOMContentLoaded", function() {
    // Add Period button
    document.getElementById('btnAddPeriod').addEventListener('click', function() {
        addRow();
    });

    // Dropdown change → load periods
    document.getElementById('source_timetable').addEventListener('change', function() {
        loadPeriods(this.value);
    });

    // Auto load periods if a timetable is preselected AND no old validation errors
    let hasOld = @json(old('periods') ? true : false);
    if (!hasOld) {
        let selected = document.getElementById('source_timetable').value;
        if (selected) {
            loadPeriods(selected);
        }
    }
});


</script>
@endpush
