<?php $__env->startSection('page_title', 'Admin — Loan Management'); ?>

<?php $__env->startSection('content'); ?>


<div class="card border-0 shadow-sm mb-5">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">
            <span class="badge bg-warning text-dark me-2"><?php echo e($pendingLoans->count()); ?></span>
            Pending Loan Applications
        </h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Applicant</th>
                        <th>Loan Type</th>
                        <th>Principal</th>
                        <th>EMI</th>
                        <th>Tenure</th>
                        <th>Applied</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium"><?php echo e($loan->user->name); ?></div>
                                <div class="text-muted small"><?php echo e($loan->user->email); ?></div>
                            </td>
                            <td><?php echo e($loan->loanProduct->name ?? '—'); ?></td>
                            <td class="fw-bold">৳<?php echo e(number_format($loan->principal, 2)); ?></td>
                            <td>৳<?php echo e(number_format($loan->monthly_installment, 2)); ?>/mo</td>
                            <td><?php echo e($loan->tenure_months); ?> months</td>
                            <td class="text-muted small"><?php echo e($loan->created_at->format('d M Y')); ?></td>
                            <td class="text-end pe-4">
                                <form action="<?php echo e(route('admin.loans.approve', $loan)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success me-1"
                                        onclick="return confirm('Approve and disburse ৳<?php echo e(number_format($loan->principal,2)); ?> to <?php echo e($loan->user->name); ?>?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.loans.reject', $loan)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Reject this loan application?')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No pending loan applications.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0">
            <span class="badge bg-success me-2"><?php echo e($activeLoans->count()); ?></span>
            Active Loans
        </h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Customer</th>
                        <th>Principal</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>EMI</th>
                        <th>Maturity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $activeLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium"><?php echo e($loan->user->name); ?></div>
                                <div class="text-muted small">AC: <?php echo e($loan->account->account_number ?? '—'); ?></div>
                            </td>
                            <td>৳<?php echo e(number_format($loan->principal, 2)); ?></td>
                            <td class="text-success">৳<?php echo e(number_format(($loan->principal * (1 + ($loan->interest_rate * $loan->tenure_months / 1200))) - $loan->outstanding_balance, 2)); ?></td>
                            <td class="text-danger fw-bold">৳<?php echo e(number_format($loan->outstanding_balance, 2)); ?></td>
                            <td>৳<?php echo e(number_format($loan->monthly_installment, 2)); ?>/mo</td>
                            <td><?php echo e($loan->maturity_date ? $loan->maturity_date->format('d M Y') : '—'); ?></td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Active</span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No active loans.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/admin/loans.blade.php ENDPATH**/ ?>