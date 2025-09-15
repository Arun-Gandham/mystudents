@extends('tenant.layouts.layout1')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
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

    <form method="POST" action="{{ tenant_route('tenant.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <x-alert-errors />
        <!-- Profile Image Upload with Preview -->
        <div class="mb-3 text-center">
            <label class="form-label d-block">Profile Photo</label>
            <img id="previewImg" 
                 src="{{ $user->staff->photo ? asset('storage/'.$user->staff->photo) : asset('images/default-avatar.png') }}" 
                 class="rounded-circle mb-2" width="120" height="120" alt="Profile Photo">
            <input type="file" name="photo" class="form-control mt-2" 
                   accept="image/*" onchange="previewFile(this);">
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email (non-editable)</label>
            <input value="{{ $user->email }}" class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone (non-editable)</label>
            <input value="{{ $user->phone }}" class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input name="designation" value="{{ old('designation', $user->designation) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
        </div>

        <button class="btn btn-success">Update Profile</button>
        <a href="{{ tenant_route('tenant.profile.show') }}" class="btn btn-secondary">Cancel</a>
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
