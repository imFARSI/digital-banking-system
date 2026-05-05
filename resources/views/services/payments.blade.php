@extends('layouts.app')

@section('page_title', 'Payments & Recharge')

{{-- Page: Bill payment and mobile recharge side-by-side; recent payment history below --}}
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Utility Bill Payment</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('services.payments.bill') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pay From Card</label>
                        <select name="card_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($cards as $c)
                                <option value="{{ $c->id }}">{{ chunk_split($c->card_number, 4, ' ') }} ({{ strtoupper($c->card_type) }})</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Funds will be deducted from the linked account.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Bill Category</label>
                        <select name="bill_category_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Bill Reference / Customer ID</label>
                        <input type="text" name="payee_reference" class="form-control bg-light border-0 py-2" placeholder="e.g. 123456789" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Amount (BDT)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">৳</span>
                            <input type="number" name="amount" class="form-control bg-light border-0 py-2" min="1" step="0.01" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 py-2">Confirm Payment <i class="bi bi-arrow-right ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right column: mobile recharge form with operator + fixed amount selection --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-phone me-2 text-success"></i>Mobile Recharge</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('services.payments.recharge') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pay From Card</label>
                        <select name="card_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($cards as $c)
                                <option value="{{ $c->id }}">{{ chunk_split($c->card_number, 4, ' ') }} ({{ strtoupper($c->card_type) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Operator</label>
                            <select name="operator" class="form-select bg-light border-0 py-2" required>
                                <option value="Grameenphone">Grameenphone</option>
                                <option value="Robi">Robi</option>
                                <option value="Banglalink">Banglalink</option>
                                <option value="Teletalk">Teletalk</option>
                                <option value="Airtel">Airtel</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control bg-light border-0 py-2" placeholder="01XXXXXXXXX" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Select Amount</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach([20,50,100,200,500] as $amt)
                                <button type="button" class="btn btn-sm btn-outline-secondary amount-btn px-3" data-amount="{{ $amt }}">৳{{ $amt }}</button>
                            @endforeach
                        </div>
                        <select name="amount" class="form-select bg-light border-0 py-2" id="rechargeAmount" required>
                            @foreach([10,20,50,100,200,300,500] as $amt)
                                <option value="{{ $amt }}">৳{{ $amt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 py-2">Recharge Now <i class="bi bi-phone-fill ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- Full-width payment history table with type, reference, category, date, amount --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0">Recent Payments History</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 border-0">Type</th>
                                <th class="border-0">Reference</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Date</th>
                                <th class="text-end pe-4 border-0">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-{{ $payment->payment_type == 'mobile_recharge' ? 'success' : 'primary' }} bg-opacity-10 text-{{ $payment->payment_type == 'mobile_recharge' ? 'success' : 'primary' }} rounded-pill px-3 py-1">
                                            {{ str_replace('_', ' ', ucfirst($payment->payment_type)) }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $payment->payee_reference }}</td>
                                    <td>{{ $payment->billCategory->name ?? 'N/A' }}</td>
                                    <td class="text-muted small">{{ $payment->paid_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end pe-4 fw-bold">৳{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted border-0">No recent payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('rechargeAmount').value = btn.dataset.amount;
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('btn-brand','text-white'));
        btn.classList.add('btn-brand','text-white');
    });
});
</script>
@endsection
