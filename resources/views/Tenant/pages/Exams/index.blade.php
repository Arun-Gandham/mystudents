@extends('tenant.baselayout')
@section('title','Exams')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h2>Exams</h2>
        <a href="{{ tenant_route('tenant.exams.create') }}" class="btn btn-primary">+ Create Exam</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Exam</th>
                <th>Academic Year</th>
                <th>Section</th>
                <th>Duration</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($exams as $exam)
            <tr>
                <td>{{ $exam->name }}</td>
                <td>{{ $exam->academic->name }}</td>
                <td>{{ $exam->section->grade->name }} - {{ $exam->section->name }}</td>
                <td>{{ $exam->starts_on }} - {{ $exam->ends_on }}</td>
                <td>{{ $exam->is_published ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ tenant_route('tenant.exams.show',['exam' => $exam]) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ tenant_route('tenant.exams.edit',['exam' => $exam]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ tenant_route('tenant.exams.results.edit',['exam' => $exam]) }}" class="btn btn-sm btn-primary">Update Results</a>
                    <form method="POST" action="{{ tenant_route('tenant.exams.destroy',['exam' => $exam]) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this exam?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $exams->links() }}
</div>
@endsection
