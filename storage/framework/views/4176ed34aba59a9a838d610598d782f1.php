<?php $__env->startSection('page_title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">All Customers</h5>
        <p class="text-muted small mb-0">View, search and manage all registered customer accounts.</p>
    </div>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-brand">
        <i class="bi bi-person-plus me-1"></i> Add User Manually
    </a>
</div>


<form method="GET" class="mb-4">
    <div class="input-group" style="max-width:380px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email…" value="<?php echo e($search ?? ''); ?>">
        <button class="btn btn-brand" type="submit"><i class="bi bi-search"></i></button>
        <?php if($search): ?>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </div>
</form>


<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-muted small"><?php echo e($loop->iteration + ($users->currentPage() - 1) * $users->perPage()); ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                 style="width:34px;height:34px;font-size:0.85rem;flex-shrink:0;">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="fw-medium text-decoration-none text-brand">
                                <?php echo e($user->name); ?>

                            </a>
                        </div>
                    </td>
                    <td class="small text-muted"><?php echo e($user->email); ?></td>
                    <td class="small"><?php echo e($user->phone); ?></td>
                    <td class="small text-capitalize"><?php echo e($user->gender); ?></td>
                    <td class="small text-muted"><?php echo e($user->created_at->format('d M Y')); ?></td>
                    <td>
                        <span class="badge <?php echo e($user->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                            <?php echo e(ucfirst($user->status)); ?>

                        </span>
                    </td>
                    <td>
                        <form action="<?php echo e(route('admin.users.toggle', $user)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="btn btn-sm <?php echo e($user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                    onclick="return confirm('Are you sure you want to change this users status?')">
                                <?php echo e($user->status === 'active' ? 'Suspend' : 'Activate'); ?>

                            </button>
                        </form>
                        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline ms-1">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('DANGER: This will permanently delete user <?php echo e($user->name); ?> and ALL their accounts, transactions, and data. This cannot be undone. Proceed?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        No customers found<?php echo e($search ? ' for "' . $search . '"' : ''); ?>.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="mt-3 d-flex justify-content-end">
    <?php echo e($users->withQueryString()->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/admin/users/index.blade.php ENDPATH**/ ?>