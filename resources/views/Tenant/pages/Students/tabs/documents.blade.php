<div class="card p-3">
  <h6>Documents</h6>
  <table class="table table-sm align-middle">
    <thead>
      <tr>
        <th>Type</th>
        <th>File</th>
        <th>Issued</th>
        <th>Verified</th>
      </tr>
    </thead>
    <tbody>
      @forelse(($documents ?? $student->documents) as $doc)
        <tr>
          <td>{{ ucfirst(str_replace('_',' ', $doc->doc_type ?? 'other')) }}</td>
          <td>
            @if(!empty($doc->file_path))
              <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-decoration-none">
                <i class="bi bi-file-earmark-text me-1"></i>View
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>{{ optional($doc->issued_on)->format('Y-m-d') ?? '-' }}</td>
          <td>{{ optional($doc->verified_on)->format('Y-m-d') ?? '-' }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">No documents uploaded</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
