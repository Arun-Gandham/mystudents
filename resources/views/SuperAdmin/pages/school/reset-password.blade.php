@extends('superadmin.pages.school.base')

@php $activeTab = 'reset-password'; @endphp

@section('tabcontent')
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white"><strong><i class="bi bi-key me-1"></i>Reset Admin Password</strong></div>
  <div class="card-body">

    <form method="POST" action="{{ route('superadmin.school.resetPasswordUpdate', $school->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">New Password *</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePwd"><i class="bi bi-eye"></i></button>
          <button class="btn btn-outline-primary" type="button" id="genPwd">Generate</button>
        </div>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Save Password</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('genPwd').addEventListener('click', function(){
    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*";
    let pwd = "";
    for(let i=0; i<12; i++){ pwd += chars.charAt(Math.floor(Math.random() * chars.length)); }
    document.getElementById('password').value = pwd;
});

document.getElementById('togglePwd').addEventListener('click', function(){
    const input = document.getElementById('password');
    const icon = this.querySelector('i');
    if(input.type === "password"){
        input.type = "text";
        icon.className = "bi bi-eye-slash";
    } else {
        input.type = "password";
        icon.className = "bi bi-eye";
    }
});
</script>
@endpush
