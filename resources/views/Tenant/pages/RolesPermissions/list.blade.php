@extends('tenant.baselayout') {{-- or superadmin.baselayout --}}

@section('title', 'Roles & Permissions')

@push('styles')
  {{-- Load only on this page. Remove if your layout already includes these. --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
  </style>
@endpush

@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    {{-- 🔹 Role dropdown added --}}
    <form method="GET" id="roleSelectForm" class="ms-3">
      <select name="role_id" id="roleSelect" class="form-select">
        <option value="">-- Select Role --</option>
        @foreach($roles as $r)
          <option value="{{ $r->id }}" {{ (isset($roleId) && $roleId == $r->id) ? 'selected' : '' }}>
            {{ $r->name }}
          </option>
        @endforeach
      </select>
    </form>
  </div>

  {{-- 🔹 Only show drag-drop when a role is selected --}}
  @if($roleId)
  {{-- Wrap in a form to submit assigned permissions --}}
  <form method="post" action="#" id="permissionRoles">
    @csrf
    {{-- When you wire backend, set action to route like:
         action="{{ tenant_route('tenant.roles.permissions.store', $roleId) }}" --}}
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
</div>
@endif
@endsection

@push('scripts')
<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
    document.getElementById('roleSelectForm').submit();
  });

  // 🔹 Preassigned permissions from server for selected role
  window.PREASSIGNED = @json($assigned ?? []);
  // Optional: Pre-populate assignments from server
  // Pass an array like ['service:add','student:delete'] from controller
  window.PREASSIGNED = @json($assigned ?? []);

  const assignedZone = document.getElementById('assigned');
  const permissionsPayload = document.getElementById('permissions_payload');
  const resetBtn = document.getElementById('resetBtn');

  function groupFromId(id) {
    return (id || '').split(':')[0] || '';
  }

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
    return !!assignedZone.querySelector(`.permission-item[id="${CSS.escape(id)}"]`);
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
    assignedZone.querySelectorAll('.permission-group').forEach(group => {
      if (group.querySelectorAll('.permission-item').length === 0) group.remove();
    });
  }

  function dragstartHandler(ev) {
    ev.dataTransfer.setData('text/plain', ev.target.id);
  }

  function dragoverHandler(ev) {
    ev.preventDefault();
    assignedZone.classList.add('highlight');
  }

  function dragleaveHandler() {
    assignedZone.classList.remove('highlight');
  }

  function dropHandler(ev) {
    ev.preventDefault();
    assignedZone.classList.remove('highlight');

    const id = ev.dataTransfer.getData('text/plain');
    const el = document.getElementById(id);
    if (!el || !el.classList.contains('permission-item')) return;
    if (assignedZone.contains(el)) return;

    moveToAssigned(el);
  }

  function serializeAssigned() {
    // Return IDs of all items currently inside assigned column
    const ids = Array.from(assignedZone.querySelectorAll('.permission-item'))
      .map(el => el.id);
    return ids;
  }

  // Init draggables on left side groups
  document.querySelectorAll('[id^="available-"] .permission-item').forEach(makeDraggable);

// Pre-assign based on server data
function hydratePreassigned() {
  if (!Array.isArray(window.PREASSIGNED)) return;
  window.PREASSIGNED.forEach(id => {
    const el = document.getElementById(id);
    if (el) moveToAssigned(el);
  });
}
hydratePreassigned();


  // On form submit, store assigned permissions into hidden input
document.getElementById('permissionRoles').addEventListener('submit', function(e) {
  const assigned = serializeAssigned();
  let value = '[]'; // default
  if (Array.isArray(assigned) && assigned.length > 0) {
    value = JSON.stringify(assigned);
  }
  console.log('Submitting permissions:', value);
  permissionsPayload.value = value;
});

  // Reset: move everything back to Available
  resetBtn.addEventListener('click', () => {
    assignedZone.querySelectorAll('.permission-item').forEach(moveBackToAvailable);
    cleanupEmptyAssignedGroups();
  });
</script>
@endpush
