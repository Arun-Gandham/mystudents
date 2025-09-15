@extends('tenant.layouts.layout1')
@section('title','Documents')

@section('content')
<div class="container-fluid">
  <h2>Documents of {{ $student->first_name }}</h2>
  <a href="{{ tenant_route('tenant.documents.create',$student->id) }}" class="btn btn-primary mb-2">+ Add Document</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Type</th><th>File</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($documents as $d)
      <tr>
        <td>{{ ucfirst($d->doc_type) }}</td>
        <td><a href="{{ asset('storage/'.$d->file_path) }}" target="_blank">View</a></td>
        <td>
          <form method="POST" action="{{ tenant_route('tenant.documents.destroy',[$student->id,$d->id]) }}" style="display:inline-block">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $documents->links() }}
</div>
@endsection
