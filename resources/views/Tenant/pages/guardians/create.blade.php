@extends('tenant.layouts.layout1')
@section('title','Add Guardian')

@section('content')
<div class="container-fluid">
  <h2>Add Guardian</h2>
  <form method="POST" action="{{ tenant_route('tenant.guardians.store',$student->id) }}">
    @csrf
    <x-alert-errors />
    <div class="mb-2"><label>Name</label><input type="text" name="full_name" class="form-control" required></div>
    <div class="mb-2"><label>Relation</label><input type="text" name="relation" class="form-control" required></div>
    <div class="mb-2"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
    <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control"></div>
    <div class="mb-2"><label>Address</label><textarea name="address" class="form-control"></textarea></div>
    <div class="mb-2"><label><input type="checkbox" name="is_primary" value="1"> Primary</label></div>
    <button class="btn btn-success">Save</button>
    <a href="{{ tenant_route('tenant.guardians.index',$student->id) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
