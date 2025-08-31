@extends('superadmin.baselayout')

@section('title', 'Poacket Canteen - Add School')
@section('description', $pageDescription)

@section('content')
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @if(session('posted'))
    <pre class="bg-light p-3 border rounded">{{ json_encode(session('posted'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
  @endif
@endif

<div class="container-fluid">
  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
      <h4 class="mb-0">Schools</h4>
      <small class="text-muted">Search all fields • no pagination</small>
    </div>

    <div class="d-flex align-items-center gap-2">
      <div class="input-group">
        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
        <input id="tblSearch" type="text" class="form-control border-start-0 no-focus"
               placeholder="Search name, domain, city, status…">
      </div>
      <a id="btnAdd" href="{{ route('superadmin.school.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
      </a>
    </div>
  </div>

  {{-- Card + Table (row-clickable) --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-modern table-hover align-middle mb-0" id="schoolsTable">
          <thead class="table-light sticky-top">
            <tr>
              <th style="width:56px;">&nbsp;</th>
              <th>School</th>
              <th class="d-none d-md-table-cell">City</th>
              <th class="text-center">Students</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="schoolsTbody">
            @forelse($schools as $s)
              @php
                $isActive    = (bool)($s->is_active ?? true);
                $statusText  = $isActive ? 'Active' : 'Inactive';
                $statusClass = $isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
                $students    = $s->students_count ?? 0;
                $initial     = strtoupper(mb_substr($s->name ?? 'S', 0, 1));
              @endphp
              <tr class="row-click" data-href="{{ route('superadmin.school.show', $s->id) }}">
                <td>
                  <div class="avatar-circle"><span>{{ $initial }}</span></div>
                </td>
                <td>
                  <div class="fw-semibold">{{ $s->name }}</div>
                  <div class="text-muted small d-flex align-items-center gap-2 flex-wrap">
                    @if(!empty($s->domain))
                      <span class="text-body-tertiary"><i class="bi bi-globe me-1"></i>{{ $s->domain }}</span>
                    @endif
                    <span class="text-body-tertiary">ID: #{{ $s->id }}</span>
                  </div>
                </td>
                <td class="d-none d-md-table-cell">{{ $s->city ?? '—' }}</td>
                <td class="text-center"><span class="badge bg-info-subtle text-info"><i class="bi bi-people me-1"></i>{{ $students }}</span></td>
                <td>
                  <span class="badge status-pill {{ $statusClass }}">
                    <span class="dot {{ $isActive ? 'dot-success' : 'dot-muted' }}"></span>
                    {{ $statusText }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-4">No schools found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
      <small class="text-muted">Rows: <span id="rowsCount">{{ $schools->count() }}</span></small>
      <small class="text-muted">Tip: multi-word search narrows results (e.g., “alpha city active”).</small>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .table-modern thead th { background:#f8fafc; }
  .table-modern tbody tr:hover { background:#f8fbff; }
  .row-click { cursor: pointer; }
  .avatar-circle{
    width:40px;height:40px;border-radius:50%;
    background: var(--bs-primary-bg-subtle, #eef2ff);
    color: var(--bs-primary, #3b82f6);
    display:flex;align-items:center;justify-content:center;
    font-weight:600;border:1px solid rgba(0,0,0,.06);
  }
  .status-pill{ display:inline-flex; align-items:center; gap:.4rem; }
  .status-pill .dot{ width:.45rem;height:.45rem;border-radius:50%; display:inline-block; background: currentColor; opacity:.55; }
  .dot-success{ color:#198754; }
  .dot-muted{ color:#6c757d; }
  .sticky-top{ position:sticky; top:0; z-index:1; }

  /* Softer focus for search (remove heavy ring) */
  .no-focus:focus { box-shadow:none !important; outline:0; border-color:#ced4da; }
</style>
@endpush

@push('scripts')
<script>
// Global search (AND across all columns)
const searchInput = document.getElementById('tblSearch');
const tbody = document.getElementById('schoolsTbody');
const allRows = Array.from(tbody.querySelectorAll('tr'));
const rowsCount = document.getElementById('rowsCount');

function filterRows(q){
  const tokens = String(q||'').toLowerCase().trim().split(/\s+/).filter(Boolean);
  let visible = 0;

  document.getElementById('noRows')?.remove();

  allRows.forEach(row=>{
    if (row.id === 'noRows') return;
    if (!tokens.length) { row.classList.remove('d-none'); visible++; return; }
    const text = row.innerText.toLowerCase();
    const ok = tokens.every(t => text.includes(t));
    row.classList.toggle('d-none', !ok);
    if (ok) visible++;
  });

  if (!visible) {
    const tr = document.createElement('tr');
    tr.id = 'noRows';
    tr.innerHTML = `<td colspan="5" class="text-center text-muted py-4">No matching schools</td>`;
    tbody.appendChild(tr);
  }
  rowsCount.textContent = visible;
}

// Row click -> navigate to view
document.querySelectorAll('#schoolsTable .row-click').forEach(tr=>{
  tr.addEventListener('click', (e)=>{
    // Allow clicks on real links if you add any inside later
    if (e.target.closest('a,button,input,select,label')) return;
    window.location = tr.dataset.href;
  });
});

// Blur search when clicking Add (so it doesn’t stay focused)
document.getElementById('btnAdd').addEventListener('mousedown', ()=> searchInput.blur());

// Init
searchInput.addEventListener('input', e=> filterRows(e.target.value));
filterRows('');
</script>
@endpush
