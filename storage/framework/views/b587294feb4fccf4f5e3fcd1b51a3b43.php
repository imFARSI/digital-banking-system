<?php $__env->startSection('page_title', 'Savings (DPS/FDR)'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Savings Plans</h5>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#savingsModal">
            <i class="bi bi-plus-lg me-1"></i>Open New Plan
        </button>
    </div>

    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Plan Type</th>
                        <th>Deposited</th>
                        <th>Rate</th>
                        <th>Tenure</th>
                        <th>Maturity Amount</th>
                        <th>Maturity Date</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $savings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold"><?php echo e(strtoupper($plan->plan_type)); ?></span>
                            </td>
                            <td>৳<?php echo e(number_format($plan->deposit_amount, 2)); ?></td>
                            <td class="fw-medium text-brand"><?php echo e($plan->interest_rate); ?>%</td>
                            <td><?php echo e($plan->tenure_months); ?> Months</td>
                            <td class="fw-bold text-success">৳<?php echo e(number_format($plan->maturity_amount, 2)); ?></td>
                            <td class="text-muted small"><?php echo e($plan->maturity_date->format('d M Y')); ?></td>
                            <td class="text-end pe-4">
                                <span class="badge bg-<?php echo e($plan->status=='active' ? 'success' : 'secondary'); ?> px-3 py-2 rounded-pill"><?php echo e(ucfirst($plan->status)); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-piggy-bank fs-1 d-block mb-3 opacity-25"></i>
                                No active savings plans found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="savingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('services.savings.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Open Savings Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Plan Type</label>
                        <select name="plan_type" class="form-select bg-light border-0 py-2" required>
                            <option value="dps">DPS — Monthly Deposit Scheme (6% p.a.)</option>
                            <option value="fdr">FDR — Fixed Deposit Receipt (7.5% p.a.)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Debit From Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-2" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> — ৳<?php echo e(number_format($acc->balance, 2)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Initial Deposit (BDT)</label>
                            <input type="number" name="deposit_amount" class="form-control bg-light border-0 py-2" min="500" placeholder="e.g. 5000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tenure (Months)</label>
                            <input type="number" name="tenure_months" class="form-control bg-light border-0 py-2" min="3" max="120" placeholder="e.g. 24" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">Open Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/services/savings.blade.php ENDPATH**/ ?>