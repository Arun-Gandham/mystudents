<div class="card p-3">
  <h6>Recent Attendance</h6>
  <table class="table table-sm align-middle">
    <thead>
      <tr>
        <th>Date</th>
        <th>Session</th>
        <th>Status</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      @forelse($recentAttendance ?? [] as $row)
        <tr>
          <td>{{ optional($row->sheet?->attendance_date)->format('Y-m-d') }}</td>
          <td>{{ $row->sheet?->session ?? '-' }}</td>
          <td>
            @php $status = strtolower($row->status ?? ''); @endphp
            <span class="badge {{ $status==='present' ? 'bg-success' : ($status==='absent' ? 'bg-danger' : 'bg-secondary') }}">{{ ucfirst($row->status ?? '-') }}</span>
          </td>
          <td>{{ $row->check_in ? $row->check_in->format('H:i') : '-' }}</td>
          <td>{{ $row->check_out ? $row->check_out->format('H:i') : '-' }}</td>
          <td class="text-muted small">{{ $row->remarks ?? '' }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">No attendance records</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
