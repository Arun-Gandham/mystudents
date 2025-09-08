<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>Rank</th>
            <th>Student</th>
            @foreach($exam->subjects as $sub)
                <th>{{ $sub->subject->name }}</th>
            @endforeach
            <th>Total</th>
            <th>Grade</th>
            <th>%</th>
        </tr>
    </thead>
    <tbody>
        @foreach(
            $exam->overallResults
                 ->sortBy('rank') // ✅ sort by stored rank
                 ->map(fn($o) => $exam->section->enrollments->firstWhere('student_id',$o->student_id))
                 ->filter()
            as $enroll
        )
            @php
                $student = $enroll->student;
                $overall = $exam->overallResults->firstWhere('student_id',$student->id);
                $results = $exam->results->where('student_id',$student->id);

                // Styling + medal based on stored rank
                $rankClass = '';
                $medal = '';
                if ($overall->rank === 1) { $rankClass = 'bg-warning bg-opacity-25 fw-bold'; $medal = '🥇'; }
                elseif ($overall->rank === 2) { $rankClass = 'bg-secondary bg-opacity-25 fw-bold'; $medal = '🥈'; }
                elseif ($overall->rank === 3) { $rankClass = 'bg-danger bg-opacity-25 fw-bold'; $medal = '🥉'; }
            @endphp
            <tr class="{{ $rankClass }}">
                <td><span class="fw-bold">{{ $medal }} {{ $overall->rank }}</span></td>
                <td>{{ $student->full_name }}</td>

                {{-- Subject marks --}}
                @foreach($exam->subjects as $sub)
                    @php $r = $results->firstWhere('subject_id',$sub->subject_id); @endphp
                    <td>{{ $r->marks_obtained ?? '-' }}</td>
                @endforeach

                {{-- Overall results --}}
                <td>{{ $overall->total_obtained }}/{{ $overall->total_max }}</td>
                <td><span class="badge bg-info">{{ $overall->overall_grade ?? '-' }}</span></td>
                <td>{{ $overall->percentage }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
