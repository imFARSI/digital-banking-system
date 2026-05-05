<?php $__env->startSection('title', 'Login'); ?>


<?php $__env->startSection('content'); ?>
<div class="auth-card">
    <div class="brand-header">
        <a href="/" class="brand-logo"><i class="bi bi-bank2"></i> FINEXA</a>
        <div class="brand-tagline">Welcome back to Secure Digital Banking</div>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #ff6b6b; border-radius: 10px;">
            <ul class="mb-0 ps-3 small">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Email or Username</label>
            <input type="text" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus placeholder="Enter your email or 'admin'">
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Password</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-4">
            Sign In <i class="bi bi-arrow-right ms-1"></i>
        </button>

        <p class="text-center text-muted small mb-0">
            Don't have an account? <a href="<?php echo e(route('register')); ?>" class="fw-bold">Open a Free Account</a>
        </p>
    </form>
</div>

<script>
    function togglePass() {
        const field = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/auth/login.blade.php ENDPATH**/ ?>