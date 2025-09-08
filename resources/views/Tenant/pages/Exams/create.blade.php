@extends('tenant.baselayout')
@section('title','Create Exam')

@section('content')
<div class="container-fluid">
    <h2>Create Exam</h2>

    <form method="POST" action="{{ tenant_route('tenant.exams.store') }}">
        @csrf
        <x-alert-errors />

        {{-- Exam Details --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Academic Year</label>
                <select name="academic_id" class="form-control" required>
                    @foreach($academics as $academic)
                        <option value="{{ $academic->id }}">{{ $academic->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Section</label>
                <select name="section_id" class="form-control" required>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->grade->name }} - {{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Exam Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Starts On</label>
                <input type="date" name="starts_on" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Ends On</label>
                <input type="date" name="ends_on" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control"></textarea>
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
                <tr>
                    <td>
                        <input type="checkbox" name="subjects[{{ $subject->id }}][id]" value="{{ $subject->id }}">
                    </td>
                    <td>{{ $subject->name }}</td>
                    <td><input type="number" name="subjects[{{ $subject->id }}][max_marks]" class="form-control" disabled></td>
                    <td><input type="number" name="subjects[{{ $subject->id }}][pass_marks]" class="form-control" disabled></td>
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
                @for($i=0;$i<5;$i++)
                <tr>
                    <td><input type="text" name="grades[{{$i}}][grade]" class="form-control" placeholder="A+"></td>
                    <td><input type="number" name="grades[{{$i}}][min_mark]" class="form-control"></td>
                    <td><input type="number" name="grades[{{$i}}][max_mark]" class="form-control"></td>
                    <td><input type="text" name="grades[{{$i}}][remark]" class="form-control" placeholder="Excellent"></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Save</button>
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
