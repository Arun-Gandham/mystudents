@extends('tenant.layouts.layout1')
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
                <td>
                    {{ $exam->starts_on?->format('d M Y') ?? '-' }}
                    -
                    {{ $exam->ends_on?->format('d M Y') ?? '-' }}
                </td>
                <td>{{ $exam->is_published ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ tenant_route('tenant.exams.show',['exam' => $exam]) }}" class="btn btn-sm btn-info">View</a>
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
