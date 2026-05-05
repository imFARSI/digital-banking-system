@extends('layouts.app')

@section('page_title', 'My Cards')

{{-- Page: All user cards displayed as visual card tiles; freeze/unfreeze actions inline --}}
@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Payment Cards</h5>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#requestCardModal">
            <i class="bi bi-plus-lg me-1"></i>Request New Card
        </button>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">
            @forelse($cards as $card)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 text-white p-4 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #0A2540, #1a4b77); min-height: 200px;">
                    <div class="d-flex justify-content-between align-items-start mb-4 position-relative" style="z-index: 2;">
                        <div>
                            <span class="badge bg-white text-dark me-1">{{ strtoupper($card->card_type) }}</span>
                            @if($card->status === 'blocked')
                                <span class="badge bg-danger">FROZEN</span>
                            @else
                                <span class="badge bg-success">ACTIVE</span>
                            @endif
                        </div>
                        <i class="bi bi-wifi fs-4 opacity-75"></i>
                    </div>
                    
                    <h4 class="font-monospace mb-4 position-relative" style="z-index: 2; letter-spacing: 2px;">
                        {{ chunk_split($card->card_number, 4, ' ') }}
                    </h4>
                    
                    <div class="d-flex justify-content-between align-items-end position-relative" style="z-index: 2;">
                        <div>
                            <small class="text-white-50 d-block small">Card Holder</small>
                            <span class="fw-medium">{{ strtoupper($card->cardholder_name) }}</span>
                        </div>
                        <div class="text-end">
                            <small class="text-white-50 d-block small">Expires</small>
                            <span class="fw-medium">{{ str_pad($card->expiry_month,2,'0',STR_PAD_LEFT) }}/{{ substr($card->expiry_year,-2) }}</span>
                        </div>
                    </div>

                    <i class="bi bi-bank2 text-white opacity-5" style="position: absolute; right: -20px; bottom: -30px; font-size: 8rem; z-index: 1;"></i>
                </div>
            </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-credit-card fs-1"></i>
                    </div>
                    <h5>No active cards</h5>
                    <p class="mb-4">Request a virtual or physical card to start spending.</p>
                    <button class="btn btn-brand px-4" data-bs-toggle="modal" data-bs-target="#requestCardModal">Request Your First Card</button>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Request Card Modal: pick account and card type; CVV and card number auto-generated --}}
{{-- Request Card Modal --}}
<div class="modal fade" id="requestCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('services.cards.request') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Request New Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link to Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-2" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }} (৳{{ number_format($acc->balance, 2) }})</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Your card will be linked to this account balance.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Card Type</label>
                        <select name="card_type" class="form-select bg-light border-0 py-2" required>
                            <option value="debit">Debit Card</option>
                            <option value="credit">Credit Card</option>
                            <option value="prepaid">Prepaid Card</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
