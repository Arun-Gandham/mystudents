@extends('tenant.baselayout')
@section('title','Enter Results')

@section('content')
<div class="container">
    <h2>Enter / Update Results for Exam: {{ $exam->name }}</h2>

    <form method="POST" action="{{ tenant_route('tenant.exams.results.update',['exam' => $exam]) }}">
        @csrf @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    @foreach($exam->subjects as $sub)
                        <th>{{ $sub->subject->name }} <br><small>Max: {{ $sub->max_marks }}</small></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->full_name }}</td>
                        @foreach($exam->subjects as $sub)
                            @php
                                $existing = $exam->results->firstWhere(fn($r) => $r->student_id==$student->id && $r->subject_id==$sub->subject_id);
                            @endphp
                            <td>
                                <input type="hidden" name="results[{{ $student->id }}_{{ $sub->subject_id }}][student_id]" value="{{ $student->id }}">
                                <input type="hidden" name="results[{{ $student->id }}_{{ $sub->subject_id }}][subject_id]" value="{{ $sub->subject_id }}">
                                <input type="number" class="form-control mb-1"
                                       name="results[{{ $student->id }}_{{ $sub->subject_id }}][marks_obtained]"
                                       value="{{ $existing->marks_obtained ?? '' }}"
                                       max="{{ $sub->max_marks }}" min="0">
                                <span class="badge bg-info d-block">{{ $existing->grade ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $existing->remarks ?? '' }}</small>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Save Results</button>
        <a href="{{ tenant_route('tenant.exams.show',['exam' => $exam]) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
