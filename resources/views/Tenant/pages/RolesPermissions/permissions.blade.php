@extends('tenant.layouts.layout1') {{-- or superadmin.baselayout --}}

@section('title', 'Roles & Permissions')

@push('styles')
<style>
  .permission-group {
    border: 1px solid #dee2e6;
    border-radius: .5rem;
    padding: .5rem;
    margin-bottom: 1rem;
    background: #f8f9fa;
  }
  .permission-item {
    margin: .25rem 0;
    padding: .25rem .5rem;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: .25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: grab;
    user-select: none;
  }
  .permission-item[draggable="false"] { cursor: default; }
  .delete-btn {
    cursor: pointer;
    color: #dc3545;
    margin-left: .5rem;
    font-size: 1.1rem;
  }
  .drop-zone {
    border: 2px dashed #6c757d;
    border-radius: .5rem;
    min-height: 220px;
    padding: 1rem;
    background: #fdfdfd;
    transition: background .15s, border-color .15s;
  }
  .drop-zone.highlight {
    background: #eefaf2;
    border-color: #28a745;
  }
  .roles-tabs {
    border-right: 1px solid #dee2e6;
    height: 100%;
  }
  .roles-tabs .nav-link {
    border-radius: 0;
    text-align: left;
    padding: .75rem 1rem;
  }
  .roles-tabs .nav-link.active {
    background: var(--bs-primary);
    color: #fff;
  }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
  <div class="row">
    <!-- LEFT: Roles as tabs -->
    <div class="col-md-3 col-lg-2 roles-tabs">
      <div class="nav flex-column nav-pills">
        @foreach($roles as $r)
          <a href="{{ tenant_route('tenant.permissions.index', ['role_id' => $r->id]) }}"
             class="nav-link {{ (isset($roleId) && $roleId == $r->id) ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i>{{ $r->name }}
          </a>
        @endforeach
      </div>
    </div>

    <!-- RIGHT: Permissions -->
    <div class="col-md-9 col-lg-10">
      @if(!$roleId)
        <div class="alert alert-info">Please select a role from the left to manage permissions.</div>
      @else
        <form method="post" action="#" id="permissionRoles">
          @csrf
          {{-- 🔹 Replace `#` with actual route when wiring backend --}}
          <input type="hidden" name="permissions" id="permissions_payload" value="[]">

          <div class="row g-4">
            <!-- LEFT: Available -->
            <div class="col-md-6">
              <h5>Available Permissions</h5>
              @foreach($allPermissions as $groupName => $perms)
                <div class="permission-group" id="available-{{ $groupName }}" data-group="{{ $groupName }}">
                  <h6 class="text-capitalize">{{ $groupName }}</h6>
                  @foreach($perms as $perm)
                    <div class="permission-item" id="{{ $perm->key }}" data-group="{{ $groupName }}" draggable="true">
                      {{ $perm->key }}
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>

            <!-- RIGHT: Assigned -->
            <div class="col-md-6">
              <h5>Assigned Permissions</h5>
              <div id="assigned" class="drop-zone"
                   ondragover="dragoverHandler(event)"
                   ondragleave="dragleaveHandler(event)"
                   ondrop="dropHandler(event)">
                <p class="text-muted m-0">Drag permissions here…</p>
              </div>

              <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save me-1"></i> Save
                </button>
                <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </button>
              </div>
            </div>
          </div>
        </form>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Pre-assigned permissions from backend
  window.PREASSIGNED = @json($assigned ?? []);

  const assignedZone = document.getElementById('assigned');
  const permissionsPayload = document.getElementById('permissions_payload');
  const resetBtn = document.getElementById('resetBtn');

  function groupFromId(id) { return (id || '').split(':')[0] || ''; }

  function ensureAssignedGroup(groupName) {
    let groupDiv = document.getElementById('assigned-' + groupName);
    if (!groupDiv) {
      groupDiv = document.createElement('div');
      groupDiv.className = 'permission-group';
      groupDiv.id = 'assigned-' + groupName;
      groupDiv.dataset.group = groupName;
      groupDiv.innerHTML = `<h6 class="text-success text-capitalize">${groupName}</h6>`;
      assignedZone.appendChild(groupDiv);
    }
    return groupDiv;
  }

  function ensureAvailableGroup(groupName) {
    return document.getElementById('available-' + groupName);
  }

  function alreadyAssigned(id) {
    return !!assignedZone?.querySelector(`.permission-item[id="${CSS.escape(id)}"]`);
  }

  function makeDeletable(el) {
    if (el.querySelector('.delete-btn')) return;
    const btn = document.createElement('i');
    btn.className = 'bi bi-trash delete-btn';
    btn.title = 'Remove from assigned';
    btn.addEventListener('click', () => moveBackToAvailable(el));
    el.appendChild(btn);
  }

  function makeDraggable(el) {
    el.setAttribute('draggable', 'true');
    el.addEventListener('dragstart', dragstartHandler);
  }

  function makeNotDraggable(el) {
    el.setAttribute('draggable', 'false');
    el.removeEventListener('dragstart', dragstartHandler);
  }

  function moveToAssigned(itemEl) {
    const id = itemEl.id;
    const groupName = groupFromId(id);
    if (alreadyAssigned(id)) return;
    const assignedGroup = ensureAssignedGroup(groupName);
    makeNotDraggable(itemEl);
    makeDeletable(itemEl);
    assignedGroup.appendChild(itemEl);
  }

  function moveBackToAvailable(itemEl) {
    const id = itemEl.id;
    const groupName = groupFromId(id);
    const del = itemEl.querySelector('.delete-btn');
    if (del) del.remove();
    makeDraggable(itemEl);
    const availableGroup = ensureAvailableGroup(groupName);
    availableGroup.appendChild(itemEl);
    cleanupEmptyAssignedGroups();
  }

  function cleanupEmptyAssignedGroups() {
    assignedZone?.querySelectorAll('.permission-group').forEach(group => {
      if (group.querySelectorAll('.permission-item').length === 0) group.remove();
    });
  }

  function dragstartHandler(ev) { ev.dataTransfer.setData('text/plain', ev.target.id); }
  function dragoverHandler(ev) { ev.preventDefault(); assignedZone.classList.add('highlight'); }
  function dragleaveHandler() { assignedZone.classList.remove('highlight'); }
  function dropHandler(ev) {
    ev.preventDefault(); assignedZone.classList.remove('highlight');
    const id = ev.dataTransfer.getData('text/plain');
    const el = document.getElementById(id);
    if (!el || !el.classList.contains('permission-item')) return;
    if (assignedZone.contains(el)) return;
    moveToAssigned(el);
  }

  function serializeAssigned() {
    return Array.from(assignedZone?.querySelectorAll('.permission-item') || [])
      .map(el => el.id);
  }

  // Init draggables on left
  document.querySelectorAll('[id^="available-"] .permission-item').forEach(makeDraggable);

  // Pre-assign permissions
  function hydratePreassigned() {
    if (!Array.isArray(window.PREASSIGNED)) return;
    window.PREASSIGNED.forEach(id => {
      const el = document.getElementById(id);
      if (el) moveToAssigned(el);
    });
  }
  hydratePreassigned();

  // On form submit
  document.getElementById('permissionRoles')?.addEventListener('submit', function(e) {
    const assigned = serializeAssigned();
    permissionsPayload.value = JSON.stringify(assigned);
  });

  // Reset
  resetBtn?.addEventListener('click', () => {
    assignedZone.querySelectorAll('.permission-item').forEach(moveBackToAvailable);
    cleanupEmptyAssignedGroups();
  });
</script>
@endpush
