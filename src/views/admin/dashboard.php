<?php include "adminSession.inc.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <!-- For icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Dashboard Stylesheet -->
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
</head>
<body>
    <?php include "../../templates/admin-sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        $role = 'admin';
        include "../../templates/header.php";
        ?>
        
        <div class="row">
            <!-- Stats Row -->
            <div class="col-6">
                <div class="row-md-6">
                    <div class="stat-card primary">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Active Patients</div>
                                <h2 id="active-patients-count" class="count">0</h2>
                            </div>
                            <button class="see-details align-self-start" id="active-patients" onclick="openModal('patients')">See Details</button>
                        </div>
                    </div>
                </div>
                <div class="row-md-6">
                    <div class="stat-card dark">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Critical Patients</div>
                                <h2 id="critical-patients-count" class="count">0</h2>
                            </div>
                            <button class="see-details align-self-start" onclick="openModal('critical')">See Details</button>
                        </div>
                    </div>
                </div>
            
                <div class="row-md-6">
                    <div class="stat-card dark">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Appointments</div>
                                <h2 id="pending-appointments-count" class="count">0</h2>
                            </div>
                            <button class="see-details align-self-start" onclick="openModal('appointments')">See Details</button>
                        </div>
                    </div>
                </div>

                <div class="row-md-6">
                    <div class="stat-card" style="background-color: #2F4F4F">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Staff</div>
                                <h2 id="staff-count" class="count">0</h2>
                            </div>
                            <button class="see-details align-self-start" onclick="openModal('staff')">See Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="col-6">
                <div class="row-md">
                    <div class="chart-card">
                        <h6 class="sm-4">Patient Status Distribution</h6>
                        <canvas id="patient-status"  border-radius=8px></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal See Details -->
        <?php include '../../templates/adminDashboardModal.php' ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../../../assets/js/admin-dashboard.js"></script>
    <script src="../../../assets/js/admin-dashboardModal.js"></script>
    <!-- <script src="../../assets/js/admin-modal.js"></script> -->
    
</body>
</html>