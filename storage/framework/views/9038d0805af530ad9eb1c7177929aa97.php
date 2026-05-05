<?php $__env->startSection('page_title', 'My Cards'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Payment Cards</h5>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#requestCardModal">
            <i class="bi bi-plus-lg me-1"></i>Request New Card
        </button>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 text-white p-4 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #0A2540, #1a4b77); min-height: 200px;">
                    <div class="d-flex justify-content-between align-items-start mb-4 position-relative" style="z-index: 2;">
                        <div>
                            <span class="badge bg-white text-dark me-1"><?php echo e(strtoupper($card->card_type)); ?></span>
                            <?php if($card->status === 'blocked'): ?>
                                <span class="badge bg-danger">FROZEN</span>
                            <?php else: ?>
                                <span class="badge bg-success">ACTIVE</span>
                            <?php endif; ?>
                        </div>
                        <i class="bi bi-wifi fs-4 opacity-75"></i>
                    </div>
                    
                    <h4 class="font-monospace mb-4 position-relative" style="z-index: 2; letter-spacing: 2px;">
                        <?php echo e(chunk_split($card->card_number, 4, ' ')); ?>

                    </h4>
                    
                    <div class="d-flex justify-content-between align-items-end position-relative" style="z-index: 2;">
                        <div>
                            <small class="text-white-50 d-block small">Card Holder</small>
                            <span class="fw-medium"><?php echo e(strtoupper($card->cardholder_name)); ?></span>
                        </div>
                        <div class="text-end">
                            <small class="text-white-50 d-block small">Expires</small>
                            <span class="fw-medium"><?php echo e(str_pad($card->expiry_month,2,'0',STR_PAD_LEFT)); ?>/<?php echo e(substr($card->expiry_year,-2)); ?></span>
                        </div>
                    </div>

                    <i class="bi bi-bank2 text-white opacity-5" style="position: absolute; right: -20px; bottom: -30px; font-size: 8rem; z-index: 1;"></i>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center text-muted py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-credit-card fs-1"></i>
                    </div>
                    <h5>No active cards</h5>
                    <p class="mb-4">Request a virtual or physical card to start spending.</p>
                    <button class="btn btn-brand px-4" data-bs-toggle="modal" data-bs-target="#requestCardModal">Request Your First Card</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="modal fade" id="requestCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('services.cards.request')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Request New Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Link to Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-2" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> (৳<?php echo e(number_format($acc->balance, 2)); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/services/cards.blade.php ENDPATH**/ ?>