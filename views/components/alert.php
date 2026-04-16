<?php if ($message = flash('success')): ?>
    <div class="alert alert-success border-0 shadow-soft"><?= e($message) ?></div>
<?php endif; ?>
<?php if ($message = flash('error')): ?>
    <div class="alert alert-danger border-0 shadow-soft"><?= e($message) ?></div>
<?php endif; ?>
