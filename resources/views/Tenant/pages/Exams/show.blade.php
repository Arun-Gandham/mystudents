@extends('tenant.baselayout')
@section('title','Exam Details')

@section('content')
<div class="container-fluid">
    <h2>Exam: {{ $exam->name }}</h2>
    <a href="{{ tenant_route('tenant.exams.results.edit',['exam' => $exam]) }}" class="btn btn-sm btn-primary">Update Results</a>
    <p><strong>Academic:</strong> {{ $exam->academic->name }}</p>
    <p><strong>Section:</strong> {{ $exam->section->grade->name }} - {{ $exam->section->name }}</p>
    <p><strong>Duration:</strong> {{ $exam->starts_on }} - {{ $exam->ends_on }}</p>
    <p><strong>Note:</strong> {{ $exam->note }}</p>

    <h4>Subjects</h4>
    <ul>
        @foreach($exam->subjects as $sub)
            <li>{{ $sub->subject->name }} (Max: {{ $sub->max_marks }}, Pass: {{ $sub->pass_marks }})</li>
        @endforeach
    </ul>

    <h4>Grading Scheme</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Grade</th>
                <th>Marks Range</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->grades as $g)
            <tr>
                <td>{{ $g->grade }}</td>
                <td>{{ $g->min_mark }} - {{ $g->max_mark }}</td>
                <td>{{ $g->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
