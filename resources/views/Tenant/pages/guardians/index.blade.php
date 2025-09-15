@extends('tenant.layouts.layout1')
@section('title','Guardians')

@section('content')
<div class="container-fluid">
  <h2>Guardians of {{ $student->first_name }}</h2>
  <a href="{{ tenant_route('tenant.guardians.create',$student->id) }}" class="btn btn-primary mb-2">+ Add Guardian</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th><th>Relation</th><th>Phone</th><th>Email</th><th>Primary</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($guardians as $g)
      <tr>
        <td>{{ $g->full_name }}</td>
        <td>{{ ucfirst($g->relation) }}</td>
        <td>{{ $g->phone_e164 }}</td>
        <td>{{ $g->email }}</td>
        <td>{{ $g->is_primary ? 'Yes':'No' }}</td>
        <td>
          <a href="{{ tenant_route('tenant.guardians.edit',[$student->id,$g->id]) }}" class="btn btn-sm btn-warning">Edit</a>
          <form method="POST" action="{{ tenant_route('tenant.guardians.destroy',[$student->id,$g->id]) }}" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Remove?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $guardians->links() }}
</div>
@endsection
