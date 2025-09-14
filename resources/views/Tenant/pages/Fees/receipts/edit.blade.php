@extends('tenant.layouts.layout1')

@section('title', 'Edit Receipt')

@section('content')
<div class="container-fluid">
    <h2>Edit Receipt</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" 
          action="{{ tenant_route('tenant.fees.fee-receipts.update', [$student->id, $receipt->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Paid On</label>
            <input type="date" name="paid_on" 
                   value="{{ old('paid_on', $receipt->paid_on->format('Y-m-d')) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" 
                   value="{{ old('total_amount', $receipt->total_amount) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <input type="text" name="method" 
                   value="{{ old('method', $receipt->method) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Reference No</label>
            <input type="text" name="reference_no" 
                   value="{{ old('reference_no', $receipt->reference_no) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Payer Name</label>
            <input type="text" name="payer_name" 
                   value="{{ old('payer_name', $receipt->payer_name) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Payer Phone</label>
            <input type="text" name="payer_phone" 
                   value="{{ old('payer_phone', $receipt->payer_phone) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Relation</label>
            <input type="text" name="payer_relation" 
                   value="{{ old('payer_relation', $receipt->payer_relation) }}" 
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Note</label>
            <textarea name="note" class="form-control">{{ old('note', $receipt->note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Receipt</button>
        <a href="{{ tenant_route('tenant.fees.fee-receipts.index',['student' => $student->id]) }}" 
           class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
