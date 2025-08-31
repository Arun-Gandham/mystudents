@props([
  'id' => 'dynForm',
  'action' => '#',
  'method' => 'POST',
  'model' => null,
  'schema' => [],
  'data' => [],

  // NEW: toggles with defaults
  'confirmSubmit' => true,
  'unsavedGuard'  => true,

  // selectors
  'trackSelector' => '[data-track="true"]',
  'safeSelector'  => '[data-safe="true"]',
])

@php
  // Coerce string props like "true"/"false" to real booleans (Blade passes strings)
  $confirmSubmit = is_bool($confirmSubmit) ? $confirmSubmit : filter_var($confirmSubmit, FILTER_VALIDATE_BOOLEAN);
  $unsavedGuard  = is_bool($unsavedGuard)  ? $unsavedGuard  : filter_var($unsavedGuard, FILTER_VALIDATE_BOOLEAN);
@endphp

@php
  use Illuminate\Support\Str;

  $val = function ($name, $default = null) use ($model) {
      return old($name, data_get($model, $name, $default));
  };

  $resolveOptions = function ($field) use ($data) {
      if (!empty($field['options'])) {
          if (is_string($field['options']) && isset($data[$field['options']])) return $data[$field['options']];
          if (is_array($field['options'])) return $field['options'];
      }
      return [];
  };
@endphp

@if($errors->any())
  <div class="alert alert-danger">
    <strong>Please fix the following:</strong>
    <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
  </div>
@endif

<form id="{{ $id }}" action="{{ $action }}" method="POST" enctype="multipart/form-data" novalidate>
  @csrf
  @if(strtoupper($method) !== 'POST') @method($method) @endif

  <div class="row g-3">
    @foreach(($schema['fields'] ?? []) as $field)
      <x-dynamic-form-field :field="$field" :val="$val" :resolveOptions="$resolveOptions" :model="$model" />
    @endforeach
  </div>

  <div class="d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-success" data-intent="confirm-submit">Save</button>
  </div>
</form>

{{-- Confirm Submit Modal --}}
<div class="modal fade" id="{{ $id }}_confirm" tabindex="-1" aria-labelledby="{{ $id }}_confirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="{{ $id }}_confirmLabel">Confirm Save</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">Are you sure you want to save these changes?</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
      <button type="button" class="btn btn-primary" id="{{ $id }}_confirmBtn">Yes, Save</button>
    </div>
  </div></div>
</div>

{{-- Unsaved Changes Modal --}}
<div class="modal fade" id="{{ $id }}_unsaved" tabindex="-1" aria-labelledby="{{ $id }}_unsavedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="{{ $id }}_unsavedLabel">Unsaved changes</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">You have unsaved changes. If you leave, those changes will be lost.</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Stay on page</button>
      <button type="button" class="btn btn-danger" id="{{ $id }}_leaveBtn">Leave without saving</button>
    </div>
  </div></div>
</div>


<script type="module">
(function(){
  if (!window.bootstrap) { console.error('Bootstrap not loaded'); return; }
  const bs = window.bootstrap;

  const formId   = @json($id);
  const form     = document.getElementById(formId);
  if (!form) return;

  @if($confirmSubmit)
  const confirmEl  = document.getElementById(formId + '_confirm');
  const confirmBtn = document.getElementById(formId + '_confirmBtn');
  const saveBtn    = form.querySelector('[data-intent="confirm-submit"]');
  const confirmModal = confirmEl ? bs.Modal.getOrCreateInstance(confirmEl) : null;

  // custom checkbox_group required check
  function checkRequiredGroups(){
    let ok = true;
    form.querySelectorAll('[data-required-group]').forEach(g=>{
      // remove previous errors
      g.querySelectorAll('.invalid-feedback.dynamic').forEach(n=>n.remove());
      g.classList.remove('is-invalid');

      const name = g.getAttribute('data-required-group');
      const anyChecked = !!form.querySelector(`input[name="${name}[]"]:checked`);
      if (!anyChecked) {
        ok = false;
        g.classList.add('is-invalid');
        const fb = document.createElement('div');
        fb.className = 'invalid-feedback d-block dynamic';
        fb.textContent = 'Please select at least one option.';
        g.appendChild(fb);
      }
    });
    return ok;
  }

  // helper: scroll to first invalid field
  function scrollToFirstInvalid(){
    let first = form.querySelector(':invalid');  // HTML5 invalids
    if (!first) {
      first = form.querySelector('.is-invalid'); // custom group
    }
    if (first) {
      first.scrollIntoView({ behavior: 'smooth', block: 'center' });
      first.focus({ preventScroll: true });
    }
  }
  saveBtn?.addEventListener('click', (e) => {
  e.preventDefault();

  // clear previous states
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

  let nativeOk = true;

  // check every required input/select/textarea
  form.querySelectorAll('[required]').forEach(el => {
    if (!el.checkValidity()) {
      el.classList.add('is-invalid');
      nativeOk = false;
    }
  });

  // custom check for checkbox groups
  const groupOk = checkRequiredGroups();

  if (!nativeOk || !groupOk) {
    scrollToFirstInvalid();
    return;
  }

  // all good -> show confirm modal
  confirmModal?.show();
});


  confirmBtn?.addEventListener('click', () => form.submit());
  @endif

})();
</script>
