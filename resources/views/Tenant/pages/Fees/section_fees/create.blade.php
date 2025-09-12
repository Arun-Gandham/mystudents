@extends('tenant.baselayout')
@section('title','Assign Section Fee')

@section('content')
<div class="container-fluid">
  <h4>Assign Fee to Section</h4>
  <form method="POST" action="{{ tenant_route('tenant.fees.section-fees.store') }}">
    @csrf
    <div class="mb-3">
      <label>Academic Year</label>
      <select name="academic_id" class="form-select" required>
        @foreach($academics as $a)
          <option value="{{ $a->id }}">{{ $a->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label>Section</label>
      <select name="section_id" class="form-select" required>
        @foreach($sections as $s)
          <option value="{{ $s->id }}">{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label>Fee Head</label>
      <select name="fee_head_id" class="form-select" required>
        @foreach($feeHeads as $h)
          <option value="{{ $h->id }}">{{ $h->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label>Amount</label>
      <input type="number" step="0.01" name="base_amount" class="form-control" required>
    </div>
    <button class="btn btn-success">Save</button>
  </form>
</div>
@endsection
