<?php 
$headerTitle = $headerTitle ?? 'Dashboard';
$buttonContent = $buttonContent ?? '';
?>

<div class="dashboard-header">
    <div class="row align-items-center">
        <?php if ($role === 'nurse' || $role === 'doctor'): ?>
            <div class="col-auto">
            <h4><?php echo $headerTitle; ?></h4>
                <div class="card text-center shadow-sm rounded-3" style="width: fit-content; height: fit-content;">
                    <div class="card-body py-3 px-4">
                        <h5 class="card-title m-0 text-muted fw-normal">
                            Welcome, <span id="username" class="text-dark fw-bold">
                                <?php echo htmlspecialchars(ucfirst(strtolower($role))); ?> <?php echo htmlspecialchars($lastName) ?>
                            </span>
                        </h5>
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