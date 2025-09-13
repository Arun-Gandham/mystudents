@extends('tenant.layouts.layout1')
@section('title','Receipt')

@section('content')
<div class="container-fluid">
  <h4>Receipt #{{ $receipt->id }}</h4>
  <p><strong>Student:</strong> {{ $receipt->student->full_name }}</p>
  <p><strong>Date:</strong> {{ $receipt->paid_on }}</p>
  <p><strong>Payer:</strong> {{ $receipt->payer_name }} ({{ $receipt->payer_relation }}) {{ $receipt->payer_phone }}</p>

  <table class="table table-bordered">
    <thead>
      <tr><th>Fee Item</th><th>Paid</th></tr>
    </thead>
    <tbody>
      @foreach($receipt->payments as $p)
        <tr>
          <td>{{ $p->item->fee_head_id }}</td>
          <td>{{ $p->paid_amount }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h5>Total Paid: {{ $receipt->total_amount }}</h5>
</div>
@endsection
