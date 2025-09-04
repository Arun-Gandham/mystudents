@extends('tenant.baselayout')

@section('title', 'Roles')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between mb-3">
        <h3>Roles</h3>
        <a href="{{ tenant_route('tenant.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Role
        </a>
    </div>
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>System Role?</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->description ?? '-' }}</td>
                <td>
                    @if($role->is_system)
                        <span class="badge bg-secondary">System</span>
                    @else
                        <span class="badge bg-success">Custom</span>
                    @endif
                </td>
                <td>
                    @if(!$role->is_system)
                        <a href="{{ tenant_route('tenant.roles.edit', ['role_id' => $role->id]) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ tenant_route('tenant.roles.destroy', ['role_id' => $role->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this role?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    @else
                        <span class="text-muted">Locked</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
