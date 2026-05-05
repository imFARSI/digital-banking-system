<?php $__env->startSection('page_title', 'Loans & Financing'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Active Loans</h5>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#applyLoanModal">
            <i class="bi bi-plus-lg me-1"></i>Apply for Loan
        </button>
    </div>

    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Loan Type</th>
                        <th>Principal</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Monthly EMI</th>
                        <th>Tenure</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?php echo e($loan->loanProduct->name ?? 'Standard Loan'); ?></div>
                                <div class="text-muted small">Status: 
                                    <?php $badgeMap = ['pending'=>'warning','active'=>'success','rejected'=>'danger','paid_off'=>'info']; ?>
                                    <span class="text-<?php echo e($badgeMap[$loan->status] ?? 'secondary'); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $loan->status))); ?></span>
                                </div>
                            </td>
                            <td>৳<?php echo e(number_format($loan->principal, 2)); ?></td>
                            <td class="text-success">৳<?php echo e(number_format(($loan->principal * (1 + ($loan->interest_rate * $loan->tenure_months / 1200))) - $loan->outstanding_balance, 2)); ?></td>
                            <td class="fw-bold text-danger">৳<?php echo e(number_format($loan->outstanding_balance, 2)); ?></td>
                            <td class="fw-medium text-brand">৳<?php echo e(number_format($loan->monthly_installment, 2)); ?></td>
                            <td><?php echo e($loan->tenure_months); ?> Mo</td>
                            <td class="text-end pe-4">
                                <?php if($loan->status === 'active' || $loan->status === 'disbursed'): ?>
                                    <button class="btn btn-sm btn-brand px-3" data-bs-toggle="modal" data-bs-target="#repayModal<?php echo e($loan->id); ?>">
                                        Repay
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cash-stack fs-1 d-block mb-3 opacity-25"></i>
                                No loan applications found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($loan->status === 'active' || $loan->status === 'disbursed'): ?>
        
        <div class="modal fade" id="repayModal<?php echo e($loan->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('services.loans.repay', $loan)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Repay Loan #<?php echo e($loan->id); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Repay From Account</label>
                                <div class="p-2 bg-light rounded text-dark fw-bold">
                                    <?php echo e($loan->account->account_number); ?>

                                </div>
                                <input type="hidden" name="account_id" value="<?php echo e($loan->account_id); ?>">
                                <div class="form-text small">Balance: ৳<?php echo e(number_format($loan->account->balance, 2)); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium d-flex justify-content-between">
                                    Repayment Amount (BDT)
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" 
                                        onclick="document.getElementById('repayAmt<?php echo e($loan->id); ?>').value = '<?php echo e($loan->outstanding_balance); ?>'">
                                        Repay Full (৳<?php echo e(number_format($loan->outstanding_balance, 2)); ?>)
                                    </button>
                                </label>
                                <input type="number" name="amount" id="repayAmt<?php echo e($loan->id); ?>" class="form-control bg-light border-0 py-2" 
                                    value="<?php echo e(number_format($loan->monthly_installment, 2, '.', '')); ?>" 
                                    max="<?php echo e($loan->outstanding_balance); ?>" min="0.01" step="any" required>
                                <div class="form-text small text-brand">Suggested EMI: ৳<?php echo e(number_format($loan->monthly_installment, 2)); ?></div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-brand px-4">Confirm Repayment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal fade" id="applyLoanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('services.loans.apply')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Apply for Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Loan Type</label>
                        <select name="loan_product_id" class="form-select bg-light border-0 py-2" required>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> — <?php echo e($p->interest_rate); ?>% Interest</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Credit to Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-2" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Principal Amount (BDT)</label>
                            <input type="number" name="principal" class="form-control bg-light border-0 py-2" min="1000" placeholder="e.g. 50000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tenure (Months)</label>
                            <input type="number" name="tenure_months" class="form-control bg-light border-0 py-2" min="1" placeholder="e.g. 12" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/services/loans.blade.php ENDPATH**/ ?>