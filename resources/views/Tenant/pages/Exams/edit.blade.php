@extends('tenant.baselayout')
@section('title','Edit Exam')

@section('content')
<div class="container-fluid">
    <h2>Edit Exam</h2>

    <form method="POST" action="{{ tenant_route('tenant.exams.update',['exam' => $exam]) }}">
        @csrf @method('PUT')
        <x-alert-errors />

        {{-- Exam Details --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Academic Year</label>
                <select name="academic_id" class="form-control">
                    @foreach($academics as $academic)
                        <option value="{{ $academic->id }}" @selected($exam->academic_id==$academic->id)>{{ $academic->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Section</label>
                <select name="section_id" class="form-control">
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected($exam->section_id==$section->id)>
                            {{ $section->grade->name }} - {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Exam Name</label>
            <input type="text" name="name" class="form-control" value="{{ $exam->name }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Starts On</label>
                <input type="date" name="starts_on" value="{{ $exam->starts_on }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Ends On</label>
                <input type="date" name="ends_on" value="{{ $exam->ends_on }}" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control">{{ $exam->note }}</textarea>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ $exam->is_published ? 'checked':'' }}>
            <label class="form-check-label">Published</label>
        </div>

        {{-- Subjects --}}
        <h4>Subjects</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Subject</th>
                    <th>Max Marks</th>
                    <th>Pass Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    @php $assigned = $exam->subjects->firstWhere('subject_id',$subject->id); @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="subjects[{{ $subject->id }}][id]" value="{{ $subject->id }}" {{ $assigned ? 'checked':'' }}>
                        </td>
                        <td>{{ $subject->name }}</td>
                        <td><input type="number" name="subjects[{{ $subject->id }}][max_marks]" value="{{ $assigned->max_marks ?? '' }}" class="form-control" {{ $assigned ? '' : 'disabled' }}></td>
                        <td><input type="number" name="subjects[{{ $subject->id }}][pass_marks]" value="{{ $assigned->pass_marks ?? '' }}" class="form-control" {{ $assigned ? '' : 'disabled' }}></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Grading --}}
        <h4>Grading Scheme</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Min Marks</th>
                    <th>Max Marks</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->grades as $g)
                <tr>
                    <td><input type="text" name="grades[{{$loop->index}}][grade]" value="{{ $g->grade }}" class="form-control"></td>
                    <td><input type="number" name="grades[{{$loop->index}}][min_mark]" value="{{ $g->min_mark }}" class="form-control"></td>
                    <td><input type="number" name="grades[{{$loop->index}}][max_mark]" value="{{ $g->max_mark }}" class="form-control"></td>
                    <td><input type="text" name="grades[{{$loop->index}}][remark]" value="{{ $g->remark }}" class="form-control"></td>
                </tr>
                @endforeach
                <tr>
                    <td><input type="text" name="grades[new][grade]" class="form-control" placeholder="A+"></td>
                    <td><input type="number" name="grades[new][min_mark]" class="form-control"></td>
                    <td><input type="number" name="grades[new][max_mark]" class="form-control"></td>
                    <td><input type="text" name="grades[new][remark]" class="form-control"></td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ tenant_route('tenant.exams.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.querySelectorAll('input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', function(){
        let row = cb.closest('tr');
        row.querySelectorAll('input[type=number]').forEach(inp => {
            inp.disabled = !cb.checked;
        });
    });
});
</script>
@endsection
