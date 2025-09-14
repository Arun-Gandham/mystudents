@extends('tenant.layouts.layout1')

@section('title', 'Receipts - '.$student->name)

@section('content')
<div class="container-fluid">
    <h2>Receipts for {{ $student->name }}</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Payer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr>
                            <td>{{ $loop->iteration + ($receipts->firstItem() - 1) }}</td>
                            <td>{{ $receipt->paid_on->format('d M Y') }}</td>
                            <td>₹{{ number_format($receipt->total_amount, 2) }}</td>
                            <td>{{ $receipt->method ?? '-' }}</td>
                            <td>{{ $receipt->reference_no ?? '-' }}</td>
                            <td>{{ $receipt->payer_name ?? '-' }}</td>
                            <td>
                                <a href="{{ tenant_route('tenant.fees.fee-receipts.show', [$student->id, $receipt->id]) }}" 
                                   class="btn btn-sm btn-primary">View</a>
                                <a href="{{ tenant_route('tenant.fees.fee-receipts.edit', [$student->id, $receipt->id]) }}" 
                                   class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No receipts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $receipts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
