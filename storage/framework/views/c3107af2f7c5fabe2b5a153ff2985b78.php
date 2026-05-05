<?php $__env->startSection('page_title', 'Manage Cards & Accounts'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <?php if($closureRequests->count() > 0): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h5 class="text-danger fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Account Closure Requests</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Balance</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $closureRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo e($req->account_number); ?></td>
                                <td><?php echo e($req->user->name); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo e(strtoupper($req->account_type)); ?></span></td>
                                <td class="fw-bold">৳<?php echo e(number_format($req->balance, 2)); ?></td>
                                <td class="text-end pe-4">
                                    <form action="<?php echo e(route('admin.accounts.approve_closure', $req)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this account? This action cannot be undone.')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-danger px-3">
                                            <i class="bi bi-trash me-1"></i> Approve Closure
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>All Platform Accounts</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Currency</th>
                                <th>Balance</th>
                                <th>Opened On</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo e($acc->account_number); ?></td>
                                <td><?php echo e($acc->user->name); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo e(strtoupper($acc->account_type)); ?></span></td>
                                <td><?php echo e($acc->currency); ?></td>
                                <td class="fw-bold">৳<?php echo e(number_format($acc->balance, 2)); ?></td>
                                <td class="text-muted small"><?php echo e(\Carbon\Carbon::parse($acc->opened_at)->format('d M Y')); ?></td>
                                <td>
                                    <?php $statusColors = ['active' => 'success', 'frozen' => 'warning', 'closed' => 'danger']; ?>
                                    <span class="badge bg-<?php echo e($statusColors[$acc->status] ?? 'secondary'); ?> bg-opacity-10 text-<?php echo e($statusColors[$acc->status] ?? 'secondary'); ?> px-3 py-1 rounded-pill">
                                        <?php echo e(ucfirst($acc->status)); ?>

                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="<?php echo e(route('admin.accounts.toggle', $acc)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm <?php echo e($acc->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success'); ?> px-3 py-1">
                                            <?php echo e($acc->status === 'active' ? 'Freeze' : 'Activate'); ?>

                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2 text-brand"></i>All Issued Cards</h5>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Card Number</th>
                                <th>Card Holder</th>
                                <th>Linked Account</th>
                                <th>Type</th>
                                <th>Expiry</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 font-monospace fw-bold text-brand">
                                    <?php echo e(chunk_split($card->card_number, 4, ' ')); ?>

                                </td>
                                <td><?php echo e($card->cardholder_name); ?></td>
                                <td>
                                    <div class="fw-medium text-dark"><?php echo e($card->account->user->name); ?></div>
                                    <div class="text-muted small">AC: <?php echo e($card->account->account_number); ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo e(strtoupper($card->card_type)); ?></span></td>
                                <td><?php echo e(str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e(substr($card->expiry_year, -2)); ?></td>
                                <td>
                                    <?php if($card->status === 'active'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill">Blocked</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="<?php echo e(route('admin.cards.toggle', $card)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm <?php echo e($card->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?> px-3 py-1">
                                            <?php echo e($card->status === 'active' ? 'Freeze' : 'Activate'); ?>

                                        </button>
                                    </form>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/admin/cards_accounts.blade.php ENDPATH**/ ?>