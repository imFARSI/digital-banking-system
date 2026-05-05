<?php $__env->startSection('page_title', 'Global Platform Transactions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%);">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50">Lifetime Transactions</h6>
                <h2 class="fw-bold">৳<?php echo e(number_format($totalVolume, 2)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-brand-accent text-white" style="background: linear-gradient(135deg, #00d4ff 0%, #0083b0 100%);">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50">Total Transaction Count</h6>
                <h2 class="fw-bold"><?php echo e(number_format($totalTxns)); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">All Transactions History</h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Type</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th class="text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 font-monospace small fw-bold text-brand"><?php echo e($txn->reference_code); ?></td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize"><?php echo e(str_replace('_', ' ', $txn->type)); ?></span>
                        </td>
                        <td>
                            <?php if($txn->senderAccount): ?>
                                <div class="fw-medium"><?php echo e($txn->senderAccount->user->name); ?></div>
                                <div class="text-muted small"><?php echo e($txn->senderAccount->account_number); ?></div>
                            <?php else: ?>
                                <span class="text-muted">SYSTEM / EXTERNAL</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($txn->receiverAccount): ?>
                                <div class="fw-medium"><?php echo e($txn->receiverAccount->user->name); ?></div>
                                <div class="text-muted small"><?php echo e($txn->receiverAccount->account_number); ?></div>
                            <?php else: ?>
                                <span class="text-muted">SYSTEM / EXTERNAL</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold">৳<?php echo e(number_format($txn->amount, 2)); ?></td>
                        <td class="text-end pe-4 text-muted small">
                            <?php echo e($txn->created_at->format('d M Y')); ?><br>
                            <?php echo e($txn->created_at->format('h:i A')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-5">No transactions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <?php echo e($transactions->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/admin/transactions.blade.php ENDPATH**/ ?>