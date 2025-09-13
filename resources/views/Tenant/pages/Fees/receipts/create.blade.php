@extends('tenant.layouts.layout1')
@section('title','New Receipt')

@section('content')
<div class="container-fluid">
  <h4>Generate Receipt for {{ $student->full_name }}</h4>

  <form method="POST" action="{{ tenant_route('tenant.fees.fee-receipts.store',['student' => $student->id]) }}">
    @csrf
    <div class="row mb-3">
      <div class="col">
        <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="Total Amount Paid">
      </div>
      <div class="col">
        <input type="date" name="paid_on" class="form-control" value="{{ now()->toDateString() }}">
      </div>
    </div>
    <div class="row mb-3">
      <div class="col"><input type="text" name="payer_name" class="form-control" placeholder="Payer Name"></div>
      <div class="col"><input type="text" name="payer_phone" class="form-control" placeholder="Payer Phone"></div>
      <div class="col"><input type="text" name="payer_relation" class="form-control" placeholder="Relation"></div>
    </div>

    <h5>Allocate Payment</h5>
    @foreach($feeItems as $item)
      @php
        $paidSoFar = $item->payments->sum('paid_amount');
        $balance   = $item->final_amount - $paidSoFar;
      @endphp
      <div class="mb-2">
        <label>{{ $item->fee_head_id }} (Balance: {{ $balance }})</label>
        <input type="number" step="0.01" name="allocations[{{ $item->id }}]" class="form-control" value="0" max="{{ $balance }}">
      </div>
    @endforeach

    <button class="btn btn-primary">Generate Receipt</button>
  </form>
</div>
@endsection
