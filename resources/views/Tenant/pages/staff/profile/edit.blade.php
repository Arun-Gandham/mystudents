@extends('tenant.baselayout')

@section('title', 'Edit Staff Profile')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ tenant_route('tenant.staff.profile.update', [$staff->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Profile Image Upload with Preview -->
        <div class="mb-3 text-center">
            <label class="form-label d-block">Profile Photo</label>
            <img id="previewImg" 
                 src="{{ $staff->photo ? asset('storage/'.$staff->photo) : asset('images/default-avatar.png') }}" 
                 class="rounded-circle mb-2" width="120" height="120" alt="Profile Photo">
            <input type="file" name="photo" class="form-control mt-2" 
                   accept="image/*" onchange="previewFile(this);">
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" value="{{ old('name', $staff->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email (non-editable)</label>
            <input value="{{ $staff->email }}" class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone (non-editable)</label>
            <input value="{{ $staff->phone }}" class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input name="designation" value="{{ old('designation', $staff->designation) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control">{{ old('address', $staff->address) }}</textarea>
        </div>

        <button class="btn btn-success">Update Profile</button>
        <a href="{{ tenant_route('tenant.staff.profile.show', ['staff' => $staff->id]) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Preview Script -->
<script>
function previewFile(input){
    let file = input.files[0];
    if(file){
        let reader = new FileReader();
        reader.onload = function(){
            document.getElementById('previewImg').src = reader.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
