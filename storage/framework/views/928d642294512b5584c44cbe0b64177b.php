<?php $__env->startSection('page_title', 'Open New Account'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h5 class="mb-0">Open a New Bank Account</h5>
                <p class="text-muted small mb-0">Choose your account type to get started.</p>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo e(route('accounts.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Account Type</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="account_type" id="savings" value="savings" checked>
                                <label class="btn btn-outline-primary w-100 py-3" for="savings">
                                    <i class="bi bi-piggy-bank d-block fs-2 mb-2"></i>
                                    <strong>Savings</strong>
                                    <small class="d-block text-muted">Earn interest on deposits</small>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="account_type" id="current" value="current">
                                <label class="btn btn-outline-primary w-100 py-3" for="current">
                                    <i class="bi bi-briefcase d-block fs-2 mb-2"></i>
                                    <strong>Current</strong>
                                    <small class="d-block text-muted">For business transactions</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Currency</label>
                        <select name="currency" class="form-select" required>
                            <option value="BDT">BDT - Bangladeshi Taka</option>
                            <option value="USD">USD - US Dollar</option>
                        </select>
                    </div>
                    <div class="alert alert-light border small">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        By opening this account you agree to Finexa's Terms & Conditions. A unique account number will be automatically generated.
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="<?php echo e(route('accounts.index')); ?>" class="btn btn-outline-secondary flex-grow-1">Cancel</a>
                        <button type="submit" class="btn btn-brand flex-grow-1">Open Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/accounts/create.blade.php ENDPATH**/ ?>