<?php $__env->startSection('page_title', 'Accounts & Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">

    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center pt-5">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3"
                     style="width:80px;height:80px;font-size:2rem;">
                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                </div>
                <h4 class="mb-1"><?php echo e(Auth::user()->name); ?></h4>
                <p class="text-muted mb-0 small">Customer ID: CUST-<?php echo e(str_pad(Auth::id(), 5, '0', STR_PAD_LEFT)); ?></p>
                <span class="badge bg-success mt-2"><?php echo e(ucfirst(Auth::user()->status)); ?></span>

                <ul class="list-group list-group-flush text-start mt-4">
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-envelope me-2"></i>Email</span>
                        <span class="fw-medium small"><?php echo e(Auth::user()->email); ?></span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-telephone me-2"></i>Phone</span>
                        <span class="fw-medium small"><?php echo e(Auth::user()->phone); ?></span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-calendar me-2"></i>DOB</span>
                        <span class="fw-medium small"><?php echo e(Auth::user()->date_of_birth->format('d M Y')); ?></span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted small"><i class="bi bi-person-vcard me-2"></i>NID</span>
                        <span class="fw-medium small"><?php echo e(Auth::user()->nid); ?></span>
                    </li>
                </ul>

                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-secondary w-100 mt-4">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Bank Accounts</h5>
                <a href="<?php echo e(route('accounts.create')); ?>" class="btn btn-brand btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Open New Account
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Account Number</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="text-end">Balance</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4">
                                        <a href="<?php echo e(route('accounts.show', $acc)); ?>" class="fw-medium text-decoration-none">
                                            <?php echo e($acc->account_number); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e(ucfirst($acc->account_type)); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($acc->status=='active' ? 'success' : ($acc->status=='frozen' ? 'warning text-dark' : 'secondary')); ?>">
                                            <?php echo e(ucfirst($acc->status)); ?>

                                        </span>
                                    </td>
                                    <td class="text-end fw-bold fs-6">৳<?php echo e(number_format($acc->balance, 2)); ?></td>
                                    <td class="text-end pe-4">
                                        <?php if($acc->status === 'active'): ?>
                                            <form action="<?php echo e(route('accounts.close', $acc)); ?>" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Request to close account <?php echo e($acc->account_number); ?>? Balance must be zero.')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Close Request</button>
                                            </form>
                                        <?php elseif($acc->status === 'frozen'): ?>
                                            <span class="text-muted small">Pending admin review</span>
                                        <?php endif; ?>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/accounts/index.blade.php ENDPATH**/ ?>