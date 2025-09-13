@extends('tenant.layouts.layout1')
@section('title','Fee Heads')

@section('content')
<div class="container-fluid">
  <h4>Fee Heads</h4>
  <a href="{{ tenant_route('tenant.fees.fee-heads.create') }}" class="btn btn-primary mb-3">+ New Fee Head</a>

  <table class="table table-bordered">
    <thead>
      <tr><th>Name</th><th>Code</th><th>Action</th></tr>
    </thead>
    <tbody>
      @foreach($feeHeads as $head)
      <tr>
        <td>{{ $head->name }}</td>
        <td>{{ $head->code }}</td>
        <td>
          <a href="{{ tenant_route('tenant.fees.fee-heads.edit',['feeHead' => $head->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form method="POST" action="{{ tenant_route('tenant.fees.fee-heads.destroy',['feeHead' => $head->id]) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
