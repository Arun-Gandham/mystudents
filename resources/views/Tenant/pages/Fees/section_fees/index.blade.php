@extends('tenant.baselayout')
@section('title','Section Fees')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Section Fees</h4>
    <a href="{{ tenant_route('tenant.fees.section-fees.create') }}" class="btn btn-primary">+ Assign Fee</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-bordered mb-0">
        <thead class="table-light">
          <tr>
            <th>Academic Year</th>
            <th>Section</th>
            <th>Fee Head</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($fees as $fee)
            <tr>
              <td>{{ $fee->academic?->name ?? '-' }}</td>
              <td>{{ $fee->section?->name ?? '-' }}</td>
              <td>{{ $fee->feeHead?->name ?? '-' }}</td>
              <td>{{ number_format($fee->base_amount,2) }}</td>
              <td>
                <span class="badge {{ $fee->is_active ? 'bg-success' : 'bg-secondary' }}">
                  {{ $fee->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted">No fees assigned yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
