@extends('tenant.baselayout')
@section('title','Exam Details')

@section('content')
<div class="container-fluid">

    {{-- 🔹 Header --}}
    {{-- 🔹 Header --}}
<div class="card shadow-sm mb-4 border-0 rounded-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            {{-- Exam details --}}
            <div>
                <h3 class="fw-bold mb-1">{{ $exam->name }}</h3>
                <p class="mb-0 text-muted">
                    <i class="bi bi-calendar-event me-1"></i> Academic: {{ $exam->academic->name }} <br>
                    <i class="bi bi-people me-1"></i> Section: {{ $exam->section->grade->name }} - {{ $exam->section->name }} <br>
                    <i class="bi bi-clock me-1"></i> Duration:
                    {{ $exam->starts_on?->format('d M Y') ?? '-' }}
                    -
                    {{ $exam->ends_on?->format('d M Y') ?? '-' }}
                </p>
            </div>

            {{-- 🔹 Status badge (top right) --}}
            <span class="badge px-3 py-2 fs-6 {{ $exam->is_published ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $exam->is_published ? '✅ Published' : '⏳ Not Published' }}
            </span>
        </div>

        {{-- 🔹 Action buttons (bottom left) --}}
        <div class="mt-3 d-flex gap-2">
            {{-- Edit Exam --}}
            <a href="{{ tenant_route('tenant.exams.edit',['exam' => $exam]) }}" class="btn btn-sm btn-warning">
                ✏️ Edit Exam
            </a>

            {{-- Update Results --}}
            <a href="{{ tenant_route('tenant.exams.results.edit',['exam' => $exam]) }}" class="btn btn-sm btn-primary">
                📄 Update Results
            </a>

            {{-- Publish/Unpublish --}}
            <form method="POST" action="{{ tenant_route('tenant.exams.toggle-publish',['exam' => $exam]) }}">
                @csrf @method('PUT')
                <button type="submit" 
                    class="btn btn-sm {{ $exam->is_published ? 'btn-outline-danger' : 'btn-outline-success' }}">
                    {{ $exam->is_published ? 'Unpublish Exam' : 'Publish Exam' }}
                </button>
            </form>
        </div>
    </div>
</div>



    <div class="row">
        {{-- 🔹 Left Tabs --}}
        <div class="col-md-3">
            <div class="nav flex-column nav-pills shadow-sm p-3 rounded-3" id="examTabs" role="tablist">
                <a class="nav-link active mb-2 py-3 px-4 fw-semibold" 
                   data-bs-toggle="list" href="#dashboard" role="tab"
                   data-url="{{ tenant_route('tenant.exams.tab',['exam'=>$exam,'tab'=>'dashboard']) }}">
                   📊 Dashboard
                </a>
                <a class="nav-link mb-2 py-3 px-4 fw-semibold"
                   data-bs-toggle="list" href="#results" role="tab"
                   data-url="{{ tenant_route('tenant.exams.tab',['exam'=>$exam,'tab'=>'results']) }}">
                   📝 Results
                </a>
                <a class="nav-link mb-2 py-3 px-4 fw-semibold"
                   data-bs-toggle="list" href="#grades" role="tab"
                   data-url="{{ tenant_route('tenant.exams.tab',['exam'=>$exam,'tab'=>'grades']) }}">
                   🏷 Grades
                </a>
            </div>
        </div>

        {{-- 🔹 Tab Content --}}
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body tab-content">
                    <div class="tab-pane fade show active p-3" id="dashboard" role="tabpanel">Loading...</div>
                    <div class="tab-pane fade p-3" id="grades" role="tabpanel">Loading...</div>
                    <div class="tab-pane fade p-3" id="results" role="tabpanel">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll('#examTabs a');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            let targetId = tab.getAttribute('href'); 
            let targetEl = document.querySelector(targetId);
            let url = tab.dataset.url;

            if (targetEl && !targetEl.dataset.loaded) {
                targetEl.innerHTML = "<div class='text-center p-5'><div class='spinner-border text-primary'></div><p class='mt-2'>Loading...</p></div>";
                fetch(url)
                    .then(res => res.text())
                    .then(html => {
                        targetEl.innerHTML = html;
                        targetEl.dataset.loaded = true;
                    })
                    .catch(err => {
                        targetEl.innerHTML = "<div class='alert alert-danger'>⚠️ Failed to load content.</div>";
                    });
            }
        });
    });

    // 🔹 Load first tab immediately
    document.querySelector('#examTabs a.active').dispatchEvent(new Event('shown.bs.tab'));
});
</script>
@endpush
