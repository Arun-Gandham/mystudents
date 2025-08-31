@extends('superadmin.pages.school.base')

@php $activeTab = 'people'; @endphp

@section('tabcontent')
<div class="row g-3">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-people me-1"></i>All Students</strong>
        <div class="input-group" style="max-width:260px;">
          <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
          <input id="studentSearch" class="form-control border-start-0" placeholder="Search students…">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="studentsTable">
            <thead class="table-light sticky-top">
              <tr>
                <th style="width:44px;">&nbsp;</th>
                <th>Name</th>
                <th>Roll</th>
                <th>Class</th>
                <th>Attendance</th>
              </tr>
            </thead>
            <tbody id="studentsTbody">
              {{-- Static sample rows --}}
              @foreach([
                ['Ananya Rao','GVH10-001','10-A','95%'],
                ['Irfan Shaikh','GVH10-002','10-A','92%'],
                ['Neha Kumar','GVH09-023','9-B','94%'],
                ['Karthik Menon','GVH08-014','8-C','90%'],
                ['Sara Thomas','GVH12-005','12-A','96%'],
              ] as $i => $st)
              @php $initial = strtoupper(mb_substr($st[0],0,1)); @endphp
              <tr>
                <td><div class="avatar-circle-sm"><span>{{ $initial }}</span></div></td>
                <td class="fw-semibold">{{ $st[0] }}</td>
                <td>{{ $st[1] }}</td>
                <td>{{ $st[2] }}</td>
                <td><span class="badge bg-info-subtle text-info">{{ $st[3] }}</span></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">Static demo list</div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white"><strong><i class="bi bi-shield-lock me-1"></i>Roles & Access</strong></div>
      <div class="card-body">
        {{-- Role cards --}}
        <div class="role-card">
          <div class="role-title"><i class="bi bi-person-gear me-1"></i>Admin</div>
          <div class="role-perms">
            <span class="perm">Manage School</span>
            <span class="perm">Users</span>
            <span class="perm">Billing</span>
            <span class="perm">Reports</span>
          </div>
        </div>
        <div class="role-card">
          <div class="role-title"><i class="bi bi-mortarboard me-1"></i>Teacher</div>
          <div class="role-perms">
            <span class="perm">Attendance</span>
            <span class="perm">Grades</span>
            <span class="perm">Homework</span>
          </div>
        </div>
        <div class="role-card">
          <div class="role-title"><i class="bi bi-cash-coin me-1"></i>Accountant</div>
          <div class="role-perms">
            <span class="perm">Fees</span>
            <span class="perm">Receipts</span>
            <span class="perm">Refunds</span>
          </div>
        </div>
        <div class="role-card">
          <div class="role-title"><i class="bi bi-bookshelf me-1"></i>Librarian</div>
          <div class="role-perms">
            <span class="perm">Catalog</span>
            <span class="perm">Issue/Return</span>
          </div>
        </div>
      </div>
      <div class="card-footer small text-muted">Static roles/permissions</div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .avatar-circle-sm{ width:32px;height:32px;border-radius:50%;background:#eef2ff;color:#3b82f6;
    display:flex;align-items:center;justify-content:center;font-weight:600;border:1px solid rgba(0,0,0,.06); }
  .role-card{ border:1px solid #eee; border-radius:.75rem; padding: .75rem .9rem; margin-bottom:.75rem; }
  .role-title{ font-weight:600; margin-bottom:.35rem; }
  .role-perms{ display:flex; gap:.4rem; flex-wrap:wrap; }
  .perm{ background:#f8f9fa; border:1px solid rgba(0,0,0,.05); border-radius:999px; padding:.2rem .5rem; font-size:.8rem; }
</style>
@endpush

@push('scripts')
<script>
  const stSearch = document.getElementById('studentSearch');
  const stBody   = document.getElementById('studentsTbody');
  const rows     = Array.from(stBody.querySelectorAll('tr'));
  function filterStudents(q){
    const t = String(q||'').toLowerCase();
    rows.forEach(r => r.classList.toggle('d-none', !r.innerText.toLowerCase().includes(t)));
  }
  stSearch?.addEventListener('input', e=> filterStudents(e.target.value));
</script>
@endpush
