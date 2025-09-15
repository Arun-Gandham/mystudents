@extends('tenant.layouts.layout1')
@section('title','Edit Address')

@section('content')
<div class="container-fluid">
  <h2>Edit Address</h2>
  <form method="POST" action="{{ tenant_route('tenant.addresses.update',[$student->id,$address->id]) }}">
    @csrf @method('PUT')
    <x-alert-errors />
    <div class="mb-2"><label>Line 1</label><input type="text" name="address_line1" class="form-control" value="{{ $address->address_line1 }}" required></div>
    <div class="mb-2"><label>Line 2</label><input type="text" name="address_line2" class="form-control" value="{{ $address->address_line2 }}"></div>
    <div class="mb-2"><label>City</label><input type="text" name="city" class="form-control" value="{{ $address->city }}" required></div>
    <div class="mb-2"><label>District</label><input type="text" name="district" class="form-control" value="{{ $address->district }}"></div>
    <div class="mb-2"><label>State</label><input type="text" name="state" class="form-control" value="{{ $address->state }}" required></div>
    <div class="mb-2"><label>Pincode</label><input type="text" name="pincode" class="form-control" value="{{ $address->pincode }}" required></div>
    <div class="mb-2"><label>Type</label>
      <select name="address_type" class="form-control" required>
        <option value="current" {{ $address->address_type=='current'?'selected':'' }}>Current</option>
        <option value="permanent" {{ $address->address_type=='permanent'?'selected':'' }}>Permanent</option>
      </select>
    </div>
    <button class="btn btn-success">Update</button>
    <a href="{{ tenant_route('tenant.addresses.index',$student->id) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
