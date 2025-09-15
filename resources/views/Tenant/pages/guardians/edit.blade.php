@extends('tenant.layouts.layout1')
@section('title','Edit Guardian')

@section('content')
<div class="container-fluid">
  <h2>Edit Guardian</h2>
  <form method="POST" action="{{ tenant_route('tenant.guardians.update',[$student->id,$guardian->id]) }}">
    @csrf @method('PUT')
    <x-alert-errors />
    <div class="mb-2"><label>Name</label><input type="text" name="full_name" class="form-control" value="{{ $guardian->full_name }}" required></div>
    <div class="mb-2"><label>Relation</label><input type="text" name="relation" class="form-control" value="{{ $guardian->relation }}"></div>
    <div class="mb-2"><label>Phone</label><input type="text" name="phone" class="form-control" value="{{ $guardian->phone_e164 }}"></div>
    <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $guardian->email }}"></div>
    <div class="mb-2"><label>Address</label><textarea name="address" class="form-control">{{ $guardian->address }}</textarea></div>
    <div class="mb-2"><label><input type="checkbox" name="is_primary" value="1" {{ $guardian->is_primary?'checked':'' }}> Primary</label></div>
    <button class="btn btn-success">Update</button>
    <a href="{{ tenant_route('tenant.guardians.index',$student->id) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
