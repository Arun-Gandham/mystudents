@extends('tenant.layouts.layout1')
@section('title','Add Address')

@section('content')
<div class="container-fluid">
  <h2>Add Address</h2>
  <form method="POST" action="{{ tenant_route('tenant.addresses.store',$student->id) }}">
    @csrf
    <x-alert-errors />
    <div class="mb-2"><label>Line 1</label><input type="text" name="address_line1" class="form-control" required></div>
    <div class="mb-2"><label>Line 2</label><input type="text" name="address_line2" class="form-control"></div>
    <div class="mb-2"><label>City</label><input type="text" name="city" class="form-control" required></div>
    <div class="mb-2"><label>District</label><input type="text" name="district" class="form-control"></div>
    <div class="mb-2"><label>State</label><input type="text" name="state" class="form-control" required></div>
    <div class="mb-2"><label>Pincode</label><input type="text" name="pincode" class="form-control" required></div>
    <div class="mb-2"><label>Type</label>
      <select name="address_type" class="form-control" required>
        <option value="current">Current</option>
        <option value="permanent">Permanent</option>
      </select>
    </div>
    <button class="btn btn-success">Save</button>
    <a href="{{ tenant_route('tenant.addresses.index',$student->id) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
