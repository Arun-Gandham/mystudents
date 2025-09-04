@extends('tenant.baselayout')

@section('title', 'Subjects')

@section('content')
<div class="container-fluid">
    <h2>Subjects</h2>

    <a href="{{ tenant_route('tenant.subjects.create') }}" class="btn btn-primary mb-3">+ Add Subject</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
                <tr>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->code }}</td>
                    <td>{{ $subject->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ tenant_route('tenant.subjects.edit',['id' => $subject->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ tenant_route('tenant.subjects.destroy', ['id' => $subject->id]) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No subjects found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
