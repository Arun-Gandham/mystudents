@extends('tenant.baselayout')
@section('title', 'Student Fee Items')

@section('content')
<div class="container-fluid py-4">
  <h4>Fee Items for {{ $student->full_name }}</h4>

  <div class="card shadow-sm p-3">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Fee Head</th>
          <th>Base Amount</th>
          <th>Discount</th>
          <th>Final Amount</th>
          <th>Paid</th>
          <th>Balance</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          @php
            $paid = $item->payments->sum('paid_amount');
            $balance = $item->final_amount - $paid;
          @endphp
          <tr>
            <td>{{ $item->feeHead->name }}</td>
            <td>{{ number_format($item->base_amount, 2) }}</td>
            <td>
              {{ ucfirst($item->discount_kind) }} 
              @if($item->discount_kind !== 'none')
                ({{ $item->discount_value }})
              @endif
            </td>
            <td>{{ number_format($item->final_amount, 2) }}</td>
            <td>{{ number_format($paid, 2) }}</td>
            <td>{{ number_format($balance, 2) }}</td>
            <td>
              <a href="{{ tenant_route('tenant.fees.student-fee-items.edit', ['item' => $item->id]) }}" 
                 class="btn btn-sm btn-warning">Edit</a>

              <form action="{{ tenant_route('tenant.fees.student-fee-items.destroy', ['item' => $item->id]) }}"
                    method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure to delete this item?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-muted text-center">No fee items assigned.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
