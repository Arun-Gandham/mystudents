{{-- resources/views/tenant/academic_years/index.blade.php --}}
@extends('tenant.baselayout')

@section('content')
<div class="container">
    <h1>Academic Years</h1>
    <a href="{{ tenant_route('tenant.academic_years.create') }}" class="btn btn-primary mb-3">Add Academic Year</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($years as $year)
                <tr>
                    <td>{{ $year->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($year->start_date)->format('d-M-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($year->end_date)->format('d-M-Y') }}</td>
                    <td>
                        <form action="{{ tenant_route('tenant.academic_years.toggle', ['academic_year' => $year->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label class="form-check form-switch">
                                <input type="checkbox" class="form-check-input"
                                       onChange="this.form.submit()"
                                       {{ $year->is_current ? 'checked' : '' }}>
                                {{ $year->is_current ? 'Current' : 'Inactive' }}
                            </label>
                        </form>
                    </td>
                    <td>
                        <a href="{{ tenant_route('tenant.academic_years.edit', ['academic_year_id' => $year->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
