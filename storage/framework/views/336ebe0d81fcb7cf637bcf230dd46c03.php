<?php $__env->startSection('page_title', 'User Details: ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto mb-3"
                     style="width:80px;height:80px;font-size:2.5rem;">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <h4 class="fw-bold mb-1"><?php echo e($user->name); ?></h4>
                <p class="text-muted small mb-3"><?php echo e($user->email); ?></p>
                
                <span class="badge <?php echo e($user->status === 'active' ? 'bg-success' : 'bg-danger'); ?> px-3 py-2 rounded-pill mb-4">
                    <?php echo e(ucfirst($user->status)); ?> Account
                </span>

                <div class="row text-start g-3 mt-2 border-top pt-4">
                    <div class="col-6">
                        <small class="text-muted d-block">Phone</small>
                        <span class="fw-medium small"><?php echo e($user->phone); ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Gender</small>
                        <span class="fw-medium small text-capitalize"><?php echo e($user->gender); ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">National ID</small>
                        <span class="fw-medium small"><?php echo e($user->nid); ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Joined</small>
                        <span class="fw-medium small"><?php echo e($user->created_at->format('d M Y')); ?></span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top">
                    <form action="<?php echo e(route('admin.users.toggle', $user)); ?>" method="POST" class="d-grid gap-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm <?php echo e($user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?>">
                            <i class="bi bi-shield-exclamation me-1"></i> <?php echo e($user->status === 'active' ? 'Suspend Account' : 'Activate Account'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-md-8">
        <div class="row g-4 h-100">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white h-100" style="background: linear-gradient(135deg, #0A2540 0%, #1a4b77 100%);">
                    <div class="card-body p-4">
                        <h6 class="text-white-50 mb-1">Total Liquid Balance</h6>
                        <h2 class="fw-bold mb-0">৳<?php echo e(number_format($totalBalance, 2)); ?></h2>
                        <i class="bi bi-wallet2 opacity-10" style="position: absolute; right: 20px; bottom: 10px; font-size: 4rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-brand-accent text-white h-100" style="background: linear-gradient(135deg, #00d4ff 0%, #0083b0 100%);">
                    <div class="card-body p-4">
                        <h6 class="text-white-50 mb-1">Active / Pending Loans</h6>
                        <h2 class="fw-bold mb-0"><?php echo e($activeLoansCount); ?> / <?php echo e($pendingLoansCount); ?></h2>
                        <i class="bi bi-cash-stack opacity-20" style="position: absolute; right: 20px; bottom: 10px; font-size: 4rem;"></i>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom pt-4 pb-2 px-4">
                        <h5 class="mb-0 fw-bold">Linked Accounts</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Account Number</th>
                                        <th>Type</th>
                                        <th>Balance</th>
                                        <th>Cards</th>
                                        <th class="text-end pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $user->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo e($acc->account_number); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e(strtoupper($acc->account_type)); ?></span></td>
                                        <td class="fw-bold">৳<?php echo e(number_format($acc->balance, 2)); ?></td>
                                        <td><?php echo e($acc->cards->count()); ?></td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-<?php echo e($acc->status === 'active' ? 'success' : 'secondary'); ?> bg-opacity-10 text-<?php echo e($acc->status === 'active' ? 'success' : 'secondary'); ?> px-3 py-1 rounded-pill">
                                                <?php echo e(ucfirst($acc->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Loan History</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Principal</th>
                                <th>Outstanding</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $user->loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4"><?php echo e($loan->loanProduct->name ?? 'Standard'); ?></td>
                                <td>৳<?php echo e(number_format($loan->principal, 2)); ?></td>
                                <td class="fw-bold">৳<?php echo e(number_format($loan->outstanding_balance, 2)); ?></td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-<?php echo e($loan->status === 'active' ? 'success' : ($loan->status === 'pending' ? 'warning' : 'danger')); ?> px-2 py-1 rounded">
                                        <?php echo e(ucfirst($loan->status)); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">No loans found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Platform Activity</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Type</th>
                                <th>Amount</th>
                                <th class="text-end pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium"><?php echo e(ucfirst(str_replace('_', ' ', $txn->type))); ?></div>
                                    <div class="text-muted small">Ref: <?php echo e($txn->reference_code); ?></div>
                                </td>
                                <td class="fw-bold">৳<?php echo e(number_format($txn->amount, 2)); ?></td>
                                <td class="text-end pe-4 text-muted small"><?php echo e($txn->created_at->format('d M, H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center py-4 text-muted">No recent transactions.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/admin/users/show.blade.php ENDPATH**/ ?>