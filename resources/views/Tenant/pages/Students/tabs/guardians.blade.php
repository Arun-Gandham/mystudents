<div class="card p-3">
  <h6>Guardians</h6>
  <table class="table table-sm align-middle">
    <thead>
      <tr>
        <th>Name</th>
        <th>Relation</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Primary</th>
      </tr>
    </thead>
    <tbody>
      @forelse($student->guardians as $g)
        <tr>
          <td>{{ $g->full_name }}</td>
          <td>{{ $g->relation ?? '-' }}</td>
          <td>{{ $g->phone_e164 ?? '-' }}</td>
          <td>{{ $g->email ?? '-' }}</td>
          <td>{!! $g->is_primary ? '<span class="badge bg-success">Yes</span>' : '<span class="text-muted">No</span>' !!}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No guardians added</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

