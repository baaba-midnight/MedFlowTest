<?php 
$headerTitle = $headerTitle ?? 'Dashboard';
$buttonContent = $buttonContent ?? '';
?>

<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h4><?php echo $headerTitle; ?></h4>
        </div>
        <?php if ($role === 'nurse' || $role === 'doctor'): ?>
            <div class="col-auto">
                <div class="card text-center" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Welcome, <span id="username">User</span></h5>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="ms-auto d-flex align-items-center gap-3">

        <?php if ($buttonContent != ''): ?>
            <?php if ($buttonContent === 'Add Patient'): ?>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="fas fa-plus"></i><?php echo $buttonContent; ?>
                </button>
            <?php else: ?>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i><?php echo $buttonContent; ?>
                </button>
            <?php endif; ?>
        <?php endif; ?>

        <a href="../../auth/logout.php" class="btn btn-dark">
          <i class="bi bi-box-arrow-right"></i> Log out
        </a>
    </div>
</div>