<table class="table table-bordered table-hover">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Roll No</th>
      <th>Full Name</th>
      <th>Grade</th>
      <th>Section</th>
      <th>Admission No</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse($students as $stu)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $stu->enrollments->first()->roll_no ?? '-' }}</td>
      <td>{{ $stu->full_name }}</td>
      <td>{{ $stu->enrollments->first()->grade->name ?? '-' }}</td>
      <td>{{ $stu->enrollments->first()->section->name ?? '-' }}</td>
      <td>{{ $stu->admission_no }}</td>
      <td>
        <a href="{{ tenant_route('tenant.students.show',['student'=>$stu->id]) }}" 
           class="btn btn-sm btn-info">View</a>
        <a href="{{ tenant_route('tenant.students.edit',['student'=>$stu->id]) }}" 
           class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ tenant_route('tenant.students.destroy',['student'=>$stu->id]) }}" 
              method="POST" class="d-inline">
          @csrf @method('DELETE')
          <button onclick="return confirm('Delete this student?')" 
                  class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="7" class="text-center">No students found.</td>
    </tr>
    @endforelse
  </tbody>
</table>

{{-- Ajax Pagination --}}
<div>
  {!! $students->links() !!}
</div>
