@extends('tenant.layouts.layout1')
@section('title', 'Edit Fee Item')

@section('content')
<div class="container-fluid py-4">
  <h4>Edit Fee Item - {{ $item->feeHead->name }}</h4>

  <div class="card shadow-sm p-4">
    <form method="POST" action="{{ tenant_route('tenant.fees.student-fee-items.update', ['item' => $item->id]) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Base Amount</label>
        <input type="text" class="form-control" value="{{ $item->base_amount }}" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_kind" class="form-select">
          <option value="none" {{ $item->discount_kind === 'none' ? 'selected' : '' }}>None</option>
          <option value="percent" {{ $item->discount_kind === 'percent' ? 'selected' : '' }}>Percent</option>
          <option value="flat" {{ $item->discount_kind === 'flat' ? 'selected' : '' }}>Flat</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number" name="discount_value" class="form-control"
               value="{{ $item->discount_value }}" step="0.01">
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ tenant_route('tenant.fees.student-fee-items.index', ['student' => $item->student_id]) }}" 
           class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
