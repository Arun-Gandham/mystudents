@extends('superadmin.baselayout')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('content')
<div class="container py-4">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h4 class="mb-0">Add New School</h4>
      <small class="text-muted">Provide the required details below</small>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1"><i class="bi bi-exclamation-octagon me-1"></i>There were some problems with your input:</div>
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('superadmin.school.store') }}" novalidate>
        @csrf

        <div class="row g-3">
          {{-- School name --}}
          <div class="col-md-6">
            <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
            <input
              type="text" id="name" name="name" autocomplete="off"
              class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name') }}" maxlength="150" required
              placeholder="Green Valley High School">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Domain --}}
          <div class="col-md-6">
            <label for="domain" class="form-label">Domain <span class="text-danger">*</span></label>
            <div class="input-group">
              <input
                type="text" id="domain" name="domain" autocomplete="off"
                class="form-control @error('domain') is-invalid @enderror"
                value="{{ old('domain') }}" required maxlength="255"
                placeholder="domain.pocketdomain.com">
              @error('domain') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="form-text">Enter only the domain (no “http://” or path).</div>
          </div>

          {{-- Admin Email --}}
          <div class="col-md-6">
            <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="bi bi-envelope"></i></span>
              <input
                type="email" id="admin_email" name="admin_email" autocomplete="off"
                class="form-control @error('admin_email') is-invalid @enderror"
                value="{{ old('admin_email') }}" required maxlength="255"
                placeholder="admin@example.com">
              @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          {{-- Admin Email --}}
          <div class="col-md-6">
            <label for="admin_email" class="form-label">Admin Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="bi bi-envelope"></i></span>
              <input
                type="text" id="admin_name" name="admin_name" autocomplete="off"
                class="form-control @error('admin_name') is-invalid @enderror"
                value="{{ old('admin_name') }}" required maxlength="255"
                placeholder="Admin Name">
              @error('admin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          {{-- Password --}}
          <div class="col-md-6">
            <label for="password" class="form-label">Admin Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="bi bi-lock"></i></span>
              <input
                type="password" id="password" name="password" autocomplete="off"
                class="form-control @error('password') is-invalid @enderror"
                required minlength="8" maxlength="72"
                placeholder="At least 8 characters">
              <button class="btn btn-outline-secondary" type="button" id="togglePwd" aria-label="Show/Hide password">
                <i class="bi bi-eye"></i>
              </button>
              <button class="btn btn-outline-success" type="button" id="genPwd" aria-label="Generate strong password" data-len="20">
                <i class="bi bi-magic"></i><span class="d-none d-md-inline ms-1">Generate (20)</span>
              </button>
              <button class="btn btn-outline-primary" type="button" id="copyPwd" aria-label="Copy password">
                <i class="bi bi-clipboard"></i>
              </button>
              @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="form-text">Short & strong: includes A–Z, a–z, 0–9 & symbols.</div>
          </div>

          {{-- Active switch --}}
          <div class="col-md-6">
            <label class="form-label d-block">Active</label>
            {{-- ensure a value is always submitted --}}
            <input type="hidden" name="is_active" value="0">
            <div class="form-check form-switch">
              <input class="form-check-input @error('is_active') is-invalid @enderror"
                     type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                     {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">School is active</label>
              @error('is_active') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="javascript:history.back()" class="btn btn-light">Cancel</a>
          <button type="submit" class="btn btn-primary">Create School</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const input  = document.getElementById('password');
  const btnGen = document.getElementById('genPwd');
  const btnCpy = document.getElementById('copyPwd');
  const btnTgl = document.getElementById('togglePwd');

  // Character sets (exclude ambiguous I/l/1/O/0)
  const U = 'ABCDEFGHJKLMNPQRSTUVWXYZ'.split('');   // no I/O
  const L = 'abcdefghijkmnopqrstuvwxyz'.split('');  // no l
  const D = '23456789'.split('');                   // no 0/1
  const S = '!@#$%^&*()-_=+[]{}:,.?/~'.split('');   // symbols
  const POOL = U.concat(L, D, S);

  function rint(max){
    const a = new Uint32Array(1);
    crypto.getRandomValues(a);
    return a[0] % max;
  }
  function pick(set){ return set[rint(set.length)]; }
  function shuffle(arr){
    for (let i = arr.length - 1; i > 0; i--){
      const j = rint(i + 1);
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
  }

  function generateStrongPassword(len){
    // Ensure 4-class complexity
    const req = [pick(U), pick(L), pick(D), pick(S)];
    while (req.length < len) req.push(pick(POOL));
    return shuffle(req.slice(0, len)).join('');
  }

  // Generate (default length from data-len, fallback to 10)
  btnGen?.addEventListener('click', ()=>{
    const len = parseInt(btnGen.dataset.len || '10', 10);
    const pwd = generateStrongPassword(Math.max(8, len)); // enforce >= 8 to satisfy validation
    input.value = pwd;

    // reveal so user can see/copy
    if (input.type === 'password') {
      input.type = 'text';
      const eye = btnTgl?.querySelector('i'); if (eye) eye.className = 'bi bi-eye-slash';
    }
    input.focus(); input.select();
  });

  // Copy
  btnCpy?.addEventListener('click', async ()=>{
    if (!input.value) return;
    try {
      await navigator.clipboard.writeText(input.value);
      const icon = btnCpy.querySelector('i'); const old = icon.className;
      icon.className = 'bi bi-clipboard-check';
      setTimeout(()=> icon.className = old, 1200);
    } catch {
      input.select(); document.execCommand('copy');
    }
  });

  // Show/Hide
  btnTgl?.addEventListener('click', ()=>{
    const eye = btnTgl.querySelector('i');
    if (input.type === 'password'){ input.type = 'text'; eye.className='bi bi-eye-slash'; }
    else { input.type = 'password'; eye.className='bi bi-eye'; }
  });
})();



(function(){
  const form = document.querySelector('form[action="{{ route('superadmin.school.store') }}"]');
  if (!form) return;

  form.addEventListener('submit', function(e){
    // normalize: trim key fields
    ['name','domain','admin_email'].forEach(id=>{
      const el = document.getElementById(id);
      if (el) el.value = el.value.trim();
    });

    // programmatic HTML5 validation even with novalidate present
    if (!form.checkValidity()){
      e.preventDefault();
      form.reportValidity();
      return;
    }
  });
})();
</script>
@endpush
