@extends('tenant.layouts.layout1')
@section('title','Enter Results')

@section('content')
<div class="container-fluid">
    <h2>Enter / Update Results for Exam: {{ $exam->name }}</h2>

    <form method="POST" action="{{ tenant_route('tenant.exams.results.update',['exam' => $exam]) }}">
        @csrf @method('PUT')

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    @foreach($exam->subjects as $sub)
                        <th>{{ $sub->subject->name }} <br>
                            <small>Max: {{ $sub->max_marks }}</small>
                        </th>
                    @endforeach
                    <th>Total</th>
                    <th>Overall Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php
                        $overall = $exam->overallResults->firstWhere('student_id', $student->id);
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $student->full_name }}</td>

                        {{-- Subject-wise marks --}}
                        @foreach($exam->subjects as $sub)
                            @php
                                $existing = $exam->results->firstWhere(fn($r) =>
                                    $r->student_id==$student->id && $r->subject_id==$sub->subject_id
                                );
                            @endphp
                            <td>
                                <input type="hidden" name="results[{{ $student->id }}_{{ $sub->subject_id }}][student_id]" 
                                       value="{{ $student->id }}">
                                <input type="hidden" name="results[{{ $student->id }}_{{ $sub->subject_id }}][subject_id]" 
                                       value="{{ $sub->subject_id }}">

                                <input type="number" class="form-control mb-1"
                                       name="results[{{ $student->id }}_{{ $sub->subject_id }}][marks_obtained]"
                                       value="{{ $existing->marks_obtained ?? '' }}"
                                       max="{{ $sub->max_marks }}" min="0">

                                <span class="badge bg-info d-block">{{ $existing->grade ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $existing->remarks ?? '' }}</small>
                            </td>
                        @endforeach

                        {{-- Overall --}}
                        <td>
                            {{ $overall->total_obtained ?? '-' }} / {{ $overall->total_max ?? $exam->subjects->sum('max_marks') }}
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $overall->overall_grade ?? '-' }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Save Results</button>
        <a href="{{ tenant_route('tenant.exams.show',['exam' => $exam]) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
