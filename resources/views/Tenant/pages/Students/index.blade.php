@extends('tenant.baselayout')
@section('title','Students')

@section('content')
<div class="container-fluid py-3">

  <h4 class="mb-3">Students</h4>

  {{-- Filters --}}
  <div class="card p-3 mb-3">
    <form id="filterForm" class="row g-2 align-items-center">
      <div class="col-md-3">
        <input type="text" name="search" id="searchInput"
               class="form-control" placeholder="Search by name or roll no">
      </div>

      <div class="col-md-3">
        <select name="grade_id" id="gradeFilter" class="form-select">
          <option value="">All Grades</option>
          @foreach(\App\Models\Grade::forSchool(current_school_id())->get() as $grade)
            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <select name="section_id" id="sectionFilter" class="form-select">
          <option value="">All Sections</option>
          @foreach(\App\Models\Section::get() as $section)
            <option value="{{ $section->id }}">{{ $section->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3 text-end">
        <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
        <a href="{{ tenant_route('tenant.students.create') }}" class="btn btn-success">+ Add Student</a>
      </div>
    </form>
  </div>

  {{-- Table container (Ajax replaces this) --}}
  <div id="studentsTable">
    @include('tenant.pages.students.partials.table',['students'=>$students])
  </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let debounceTimer;
let currentRequest = null;

// Fetch students with Ajax
function fetchStudents(pageUrl = null) {
    const formData = $("#filterForm").serialize();
    let url = pageUrl || "{{ tenant_route('tenant.students.index') }}";

    if (currentRequest) currentRequest.abort(); // cancel previous request

    currentRequest = $.ajax({
        url: url,
        data: formData,
        beforeSend: function() {
            $("#studentsTable").html("<div class='text-center p-4'>Loading...</div>");
        },
        success: function(res) {
            $("#studentsTable").html(res);
        },
        error: function(xhr) {
            if (xhr.statusText !== "abort") {
                $("#studentsTable").html("<div class='text-center text-danger p-4'>Error loading students.</div>");
            }
        }
    });
}

// Live search with debounce
$("#searchInput").on("keyup", function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchStudents();
    }, 400);
});

// Grade / Section filters
$("#gradeFilter, #sectionFilter").on("change", function() {
    fetchStudents();
});

// Reset button
$("#resetBtn").on("click", function() {
    $("#filterForm")[0].reset();
    fetchStudents();
});

// Pagination click (Ajax)
$(document).on("click", "#studentsTable .pagination a", function(e) {
    e.preventDefault();
    let url = $(this).attr("href");
    fetchStudents(url);
});
</script>
@endpush
