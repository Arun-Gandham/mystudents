@extends('tenant.baselayout')

@section('title','Edit Staff')

@section('content')
<div class="container">
    <h2>Edit Staff</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.staff.update',['id' => $staff->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name',$staff->first_name) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name',$staff->last_name) }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Surname</label>
                <input type="text" name="surname" value="{{ old('surname',$staff->surname) }}" class="form-control">
            </div>
        </div>

        {{-- User --}}
        <div class="mb-3">
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email',$staff->user->email) }}" class="form-control" required>
        </div>

        {{-- Existing Photo + Upload --}}
        <div class="mb-3">
            <label>Profile Photo</label><br>
            @if($staff->photo)
                <img id="photoPreview" src="{{ asset('storage/'.$staff->photo) }}"
                     alt="Profile Photo Preview" class="rounded mb-2" width="100" height="100">
            @else
                <img id="photoPreview" src="" alt="" class="rounded mb-2 d-none" width="100" height="100">
            @endif
            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
        </div>

        {{-- Employment Info --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Experience (Years)</label>
                <input type="number" name="experience_years" value="{{ old('experience_years',$staff->experience_years) }}" class="form-control" min="0">
            </div>
            <div class="col-md-4 mb-3">
                <label>Joining Date</label>
                <input type="date" name="joining_date" value="{{ old('joining_date',$staff->joining_date?->format('Y-m-d')) }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="designation" value="{{ old('designation',$staff->designation) }}" class="form-control">
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone',$staff->phone) }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address',$staff->address) }}" class="form-control">
            </div>
        </div>

        {{-- Roles --}}
        <div class="mb-3">
            <label>Assign Roles *</label><br>
            @foreach($roles as $role)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="role-{{ $role->id }}" class="form-check-input"
                        {{ $staff->user->roles->pluck('id')->contains($role->id) ? 'checked' : '' }}>
                    <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label>Primary Role</label>
            <select name="primary_role" class="form-control">
                <option value="">-- Select Primary Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ optional($staff->user->roles->firstWhere('pivot.is_primary', true))->id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ tenant_route('tenant.staff.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

{{-- JS Preview --}}
<script>
document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photoPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('d-none');
    }
});
</script>
@endsection
