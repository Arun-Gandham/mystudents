<div class="row g-3">
    {{-- Left: Overall Topper --}}
    <div class="col-md-5">
        @if($overallTopper)
        <div class="card shadow-lg border-0 text-center h-100" style="border-top:5px solid gold;">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-trophy-fill text-warning fs-1"></i>
                </div>
                {{-- Student Image --}}
                <img src="{{ $overallTopper->student->photo_url ?? 'https://via.placeholder.com/120x120?text=No+Image' }}"
                     class="rounded-circle mb-3" width="120" height="120" alt="Student">
                <h4 class="fw-bold text-dark">{{ $overallTopper->student->full_name }}</h4>
                <p class="mb-1 fs-5">
                    {{ $overallTopper->total_obtained }}/{{ $overallTopper->total_max }}
                    ({{ round(($overallTopper->total_obtained/$overallTopper->total_max)*100,2) }}%)
                </p>
                <span class="badge bg-info px-3 py-2">{{ $overallTopper->overall_grade }}</span>
                <div class="mt-2 text-muted">🏆 Overall Topper</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Subject Toppers --}}
    <div class="col-md-7">
        <div class="row g-3">
            @forelse($subjectToppers as $subTop)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column align-items-center text-center">
                            <h6 class="fw-bold text-primary mb-2">{{ $subTop['subject'] }}</h6>
                            <img src="{{ $subTop['student']->photo_url ?? 'https://via.placeholder.com/80x80?text=No+Image' }}"
                                 class="rounded-circle mb-2" width="80" height="80" alt="Student">
                            <p class="mb-1 fw-semibold">{{ $subTop['student']->full_name }}</p>
                            <span class="badge bg-success">
                                {{ $subTop['marks'] }}/{{ $subTop['max'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    No subject results available yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
