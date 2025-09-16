<div class="card p-3">
  <h6>Weekly Timetable</h6>
  @php
    $days = ['Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday'];
  @endphp
  @if(($timetables ?? collect())->isEmpty())
    <div class="text-muted">No timetable assigned.</div>
  @else
    @foreach($days as $short=>$label)
      @if(($timetables[$short] ?? collect())->isNotEmpty())
        <div class="mb-3">
          <div class="fw-semibold mb-1">{{ $label }}</div>
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Period</th>
                <th>Time</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Room</th>
              </tr>
            </thead>
            <tbody>
              @foreach(($timetables[$short] ?? collect()) as $dayTable)
                @foreach($dayTable->periods as $p)
                  <tr>
                    <td>{{ $p->period_no }}</td>
                    <td>{{ $p->starts_at }} - {{ $p->ends_at }}</td>
                    <td>{{ $p->subject->name ?? '-' }}</td>
                    <td>{{ $p->teacher?->full_name ?? '-' }}</td>
                    <td>{{ $p->room ?? '-' }}</td>
                  </tr>
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    @endforeach
  @endif
</div>
