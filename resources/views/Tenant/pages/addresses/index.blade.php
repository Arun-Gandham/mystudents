@extends('tenant.layouts.layout1')
@section('title','Addresses')

@section('content')
<div class="container-fluid">
  <h2>Addresses of {{ $student->first_name }}</h2>
  <a href="{{ tenant_route('tenant.addresses.create',$student->id) }}" class="btn btn-primary mb-2">+ Add Address</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Address</th><th>City</th><th>State</th><th>Pincode</th><th>Type</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($addresses as $a)
      <tr>
        <td>{{ $a->address_line1 }} {{ $a->address_line2 }}</td>
        <td>{{ $a->city }}</td>
        <td>{{ $a->state }}</td>
        <td>{{ $a->pincode }}</td>
        <td>{{ ucfirst($a->address_type) }}</td>
        <td>
          <a href="{{ tenant_route('tenant.addresses.edit',[$student->id,$a->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form method="POST" action="{{ tenant_route('tenant.addresses.destroy',[$student->id,$a->id]) }}" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $addresses->links() }}
</div>
@endsection
