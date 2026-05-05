<?php $__env->startSection('page_title', 'Transactions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-send fs-2 text-primary mb-2"></i>
            <h6>Transfer</h6>
            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#transferModal">Send Money</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-plus-circle fs-2 text-success mb-2"></i>
            <h6>Deposit</h6>
            <button class="btn btn-sm btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#depositModal">Deposit Funds</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="bi bi-dash-circle fs-2 text-danger mb-2"></i>
            <h6>Withdraw</h6>
            <button class="btn btn-sm btn-outline-danger mt-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw Cash</button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">Transaction History</h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reference</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $userAccountIds = Auth::user()->accounts->pluck('id')->toArray(); ?>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $isDebit = in_array($txn->sender_account_id, $userAccountIds); ?>
                        <tr>
                            <td class="ps-4 font-monospace text-muted small"><?php echo e($txn->reference_code); ?></td>
                            <td>
                                <div class="fw-medium small"><?php echo e($txn->created_at->format('d M Y')); ?></div>
                                <div class="text-muted" style="font-size:0.75rem;"><?php echo e($txn->created_at->format('h:i A')); ?></div>
                            </td>
                            <td class="fw-medium"><?php echo e($txn->description ?? ucfirst($txn->type)); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo e(ucfirst(str_replace('_',' ',$txn->type))); ?></span></td>
                            <td>
                                <?php if($txn->status == 'completed'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><?php echo e(ucfirst($txn->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4 fw-bold <?php echo e($isDebit ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e($isDebit ? '-' : '+'); ?> ৳<?php echo e(number_format($txn->amount, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No transactions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <?php echo e($transactions->links('pagination::bootstrap-5')); ?>

    </div>
</div>


<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('transactions.transfer')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0"><h5 class="modal-title">Transfer Funds</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">From Account</label>
                        <select name="sender_account_id" class="form-select" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> — ৳<?php echo e(number_format($acc->balance, 2)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recipient Account Number</label>
                        <input type="text" name="receiver_account_no" class="form-control" placeholder="e.g. FNX1234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand">Confirm Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="depositModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('transactions.deposit')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0"><h5 class="modal-title">Deposit Funds</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">To Account</label>
                        <select name="account_id" class="form-select" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> — ৳<?php echo e(number_format($acc->balance, 2)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('transactions.withdraw')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0"><h5 class="modal-title">Withdraw Cash</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">From Account</label>
                        <select name="account_id" class="form-select" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> — ৳<?php echo e(number_format($acc->balance, 2)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (BDT)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. ATM withdrawal">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/transactions/index.blade.php ENDPATH**/ ?>