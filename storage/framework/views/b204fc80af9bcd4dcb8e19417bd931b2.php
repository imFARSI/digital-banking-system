<?php $__env->startSection('page_title', 'Ticket #' . $ticket->ticket_number); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?php echo e($ticket->subject); ?></h5>
                    <span class="text-muted small">Category: <?php echo e(ucfirst($ticket->category)); ?> | Priority: <span class="fw-bold"><?php echo e(ucfirst($ticket->priority)); ?></span></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?php if($ticket->status == 'open'): ?>
                        <span class="badge bg-warning text-dark px-3 py-2">Open</span>
                    <?php elseif($ticket->status == 'resolved'): ?>
                        <span class="badge bg-success px-3 py-2">Resolved</span>
                    <?php else: ?>
                        <span class="badge bg-primary px-3 py-2"><?php echo e(ucfirst($ticket->status)); ?></span>
                    <?php endif; ?>

                    <?php if(Auth::user()->isAdmin() && $ticket->status !== 'resolved'): ?>
                        <form action="<?php echo e(route('admin.support.resolve', $ticket->id)); ?>" method="POST" class="m-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle me-1"></i> Resolve</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Thread Replies -->
                <div class="p-4" style="background-color: #fafbfa;">
                    <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card border-0 shadow-sm mb-3 <?php echo e($reply->sender_id == Auth::id() ? 'ms-5 border-start border-4 border-primary' : 'me-5 border-start border-4 border-warning'); ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="mb-0 fw-bold"><?php echo e($reply->sender->name); ?> <span class="badge bg-light text-muted fw-normal ms-2 border"><?php echo e(ucfirst($reply->sender_role)); ?></span></h6>
                                    <small class="text-muted"><?php echo e($reply->created_at->format('d M Y, h:i A')); ?></small>
                                </div>
                                <p class="mb-0" style="white-space: pre-wrap;"><?php echo e($reply->message); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <div class="card-footer bg-white border-top p-4">
                <form action="<?php echo e(Auth::user()->isAdmin() ? route('admin.support.reply', $ticket->id) : route('support.reply', $ticket->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Add a Reply</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Type your response here..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-brand px-4">Post Reply <i class="bi bi-send ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white pt-4">
                <h6 class="mb-0">Ticket Details</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <span class="text-muted d-block small">Created On</span>
                        <span class="fw-medium"><?php echo e($ticket->created_at->format('M d, Y h:i A')); ?></span>
                    </li>
                    <li class="mb-3">
                        <span class="text-muted d-block small">Last Updated</span>
                        <span class="fw-medium"><?php echo e($ticket->updated_at->diffForHumans()); ?></span>
                    </li>
                    <li>
                        <span class="text-muted d-block small">Requested By</span>
                        <span class="fw-medium"><?php echo e($ticket->user->name); ?></span>
                    </li>
                </ul>
                <hr>
                <a href="<?php echo e(Auth::user()->isAdmin() ? route('admin.support') : route('support.index')); ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-left me-2"></i>Back to Tickets</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/support/show.blade.php ENDPATH**/ ?>