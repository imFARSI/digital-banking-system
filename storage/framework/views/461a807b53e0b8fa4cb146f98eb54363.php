<?php $__env->startSection('page_title', 'Finexa Rewards'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-4 mb-4" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
            <div class="card-body p-4 text-white text-center position-relative">
                <i class="bi bi-gift fs-1 opacity-25 position-absolute" style="top: 10px; right: 20px;"></i>
                <div class="mb-2 opacity-75 fw-medium">Available Points</div>
                <h1 class="display-3 fw-bold mb-0"><?php echo e(number_format($user->reward_points)); ?></h1>
                <div class="mt-3 bg-white bg-opacity-20 rounded-pill px-3 py-1 d-inline-block small">
                    Every transaction earns 5 points
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Recent History</h5>
            </div>
            <div class="card-body p-4">
                <div class="text-center py-5 text-muted opacity-50">
                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                    <p class="small mb-0">Redemption history will appear here.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="mb-0 fw-bold">Redeem Points for Cashback</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo e(route('services.rewards.redeem')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">Choose Your Reward Plan</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="points" id="plan15" value="15" checked>
                                <label class="btn btn-outline-primary w-100 p-3 rounded-4 border-2 h-100 d-flex flex-column align-items-center justify-content-center" for="plan15">
                                    <div class="fw-bold fs-4">৳5</div>
                                    <div class="small opacity-75">15 Points</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="points" id="plan50" value="50">
                                <label class="btn btn-outline-primary w-100 p-3 rounded-4 border-2 h-100 d-flex flex-column align-items-center justify-content-center" for="plan50">
                                    <div class="fw-bold fs-4">৳25</div>
                                    <div class="small opacity-75">50 Points</div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="points" id="plan100" value="100">
                                <label class="btn btn-outline-primary w-100 p-3 rounded-4 border-2 h-100 d-flex flex-column align-items-center justify-content-center" for="plan100">
                                    <div class="fw-bold fs-4">৳50</div>
                                    <div class="small opacity-75">100 Points</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Credit to Account</label>
                        <select name="account_id" class="form-select bg-light border-0 py-3 rounded-3" required>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->account_number); ?> (৳<?php echo e(number_format($acc->balance, 2)); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text mt-2 small text-muted">
                            <i class="bi bi-info-circle me-1"></i> Cashback will be instantly added to this account.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 py-3 rounded-3 fw-bold">
                        <i class="bi bi-stars me-2"></i> Redeem Now
                    </button>
                </form>

                <div class="mt-5 p-4 bg-light rounded-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-square me-2 text-brand"></i>How it works?</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2">Earn <strong>5 reward points</strong> for every transaction you make on the platform.</li>
                        <li class="mb-2">Collect points and redeem them for instant cashback anytime.</li>
                        <li class="mb-2">There are no expiration dates on your points — they stay with you forever.</li>
                        <li>Cashback is credited instantly to your selected bank account.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/services/rewards.blade.php ENDPATH**/ ?>