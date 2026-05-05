<?php $__env->startSection('page_title', 'Support Center'); ?>

<?php $__env->startSection('content'); ?>
<?php if(!Auth::user()->isAdmin()): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">Need Help?</h5>
            <p class="text-muted mb-0 small">Create a ticket and our team will get back to you shortly.</p>
        </div>
        <button class="btn btn-brand px-4 py-2" data-bs-toggle="modal" data-bs-target="#ticketModal" style="background: var(--brand-blue); color: white;">
            <i class="bi bi-plus-circle me-2"></i> New Ticket
        </button>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h5 class="mb-0"><?php echo e(Auth::user()->isAdmin() ? 'Customer Support Tickets' : 'My Support Tickets'); ?></h5>
    </div>
    <div class="card-body p-0 mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Ticket Number</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Last Updated</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 font-monospace fw-bold text-brand">
                                <a href="<?php echo e(Auth::user()->isAdmin() ? route('admin.support.show', $ticket->id) : route('support.show', $ticket->id)); ?>" class="text-decoration-none">#<?php echo e($ticket->ticket_number); ?></a>
                            </td>
                            <td class="fw-medium"><?php echo e($ticket->subject); ?></td>
                            <td><?php echo e(ucfirst($ticket->category)); ?></td>
                            <td>
                                <?php if($ticket->status == 'open'): ?>
                                    <span class="badge bg-warning text-dark px-2 py-1">Open</span>
                                <?php elseif($ticket->status == 'in_progress'): ?>
                                    <span class="badge bg-primary px-2 py-1">In Progress</span>
                                <?php elseif($ticket->status == 'resolved' || $ticket->status == 'closed'): ?>
                                    <span class="badge bg-success px-2 py-1"><?php echo e(ucfirst($ticket->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4 text-muted small">
                                <?php echo e($ticket->updated_at->diffForHumans()); ?>

                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo e(Auth::user()->isAdmin() ? route('admin.support.show', $ticket->id) : route('support.show', $ticket->id)); ?>" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;">View & Reply</a>
                                    
                                    <?php if(Auth::user()->isAdmin() && $ticket->status !== 'resolved'): ?>
                                        <form action="<?php echo e(route('admin.support.resolve', $ticket->id)); ?>" method="POST" class="m-0">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success" style="font-size:0.75rem;"><i class="bi bi-check-circle"></i> Resolve</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">You have no open support tickets.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <?php echo e($tickets->links('pagination::bootstrap-5')); ?>

    </div>
</div>

<!-- Modal: New Ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('support.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Create Support Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="account">Account Issue</option>
                                <option value="transaction">Transaction Issue</option>
                                <option value="card">Card Issue</option>
                                <option value="loan">Loan Issue</option>
                                <option value="technical">Technical Issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Describe your issue in detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Finexa\resources\views/support/index.blade.php ENDPATH**/ ?>