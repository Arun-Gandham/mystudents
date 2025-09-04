@extends('tenant.baselayout')

@section('title','Staff')

@section('content')
<div class="container-fluid">
    <h2>Staff</h2>
    <a href="{{ tenant_route('tenant.staff.create') }}" class="btn btn-primary mb-3">+ Add Staff</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $s)
                <tr>
                    <td>
                        @if($s->photo)
                            <img src="{{ asset('storage/'.$s->photo) }}" width="40" class="rounded-circle">
                        @endif
                    </td>
                    <td>{{ $s->first_name }} {{ $s->last_name }}</td>
                    <td>{{ $s->user->email }}</td>
                    <td>{{ $s->designation }}</td>
                    <td>{{ $s->user->roles->pluck('name')->join(', ') }}</td>
                    <td class="d-flex gap-1">
                        {{-- View --}}
                        <a href="{{ tenant_route('tenant.staff.show',['id' => $s->id]) }}" class="btn btn-sm btn-info">View</a>

                        {{-- Edit --}}
                        <a href="{{ tenant_route('tenant.staff.edit',['id' => $s->id]) }}" class="btn btn-sm btn-warning">Edit</a>

                        {{-- Delete --}}
                        <form action="{{ tenant_route('tenant.staff.destroy',['id' => $s->id]) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete staff?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No staff found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
