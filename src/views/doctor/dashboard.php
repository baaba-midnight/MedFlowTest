<?php
include "../../includes/config.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedFlow - Doctor Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/modalDisplayInfo.css">
    <link rel="stylesheet" href="../../../assets/css/edit.css">
    <link rel="stylesheet" href="../../../assets/css/modal.css">
    <link rel="stylesheet" href="../../../assets/css/admin-tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include "../../templates/doctor-sidebar.php"; ?>

    <!-- Main Content -->
<div class="main-content" id="mainData" data-id = "<?php echo $_SESSION['id']?>">
    <div class="dashboard-header">
        <h4 class="mb-0">Dashboard</h4>
    </div>

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-md-12">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="stat-card primary">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Total Assigned Patients</div>
                                <?php
                                //Set it to session Id
                                $user_id = 1;
                                $query = 'SELECT COUNT(*) AS assigned_patients FROM patients WHERE doctor_id = ?;';
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param('i', $user_id);
                                if ($stmt-> execute()){
                                    $results = $stmt -> get_result();
                                    $row = $results -> fetch_assoc();
                                    $assigned_patients = $row["assigned_patients"];
                                }
                                ?>
                                <h2 id="active-patients-count" class="count"><?php echo $assigned_patients ?></h2>
                                <small id="assigned-patients-change"></small>
                            </div>
                            <button class="see-details align-self-start" id="assigned-patients" data-title = "Total Assigned Patients" data-table = "critical">See Details</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card dark">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Critical Patients</div>
                                <?php
                                $user_id = 1;
                                //Set it to session Id
                                $query = 'SELECT COUNT(*) AS critical_patients
                                    FROM patients
                                    WHERE is_critical = TRUE;
                                    ';
                                $stmt = $conn->prepare($query);
                                if ($stmt-> execute()){
                                    $results = $stmt -> get_result();
                                    $row = $results -> fetch_assoc();
                                    $critical = $row["critical_patients"];
                                }

                                ?>
                                <h2 id="active-patients-count" class="count"><?php echo $critical?></h2>
                                <small id="critical-patients-change"></small>
                            </div>
                            <button class="see-details align-self-start" data-title = "Critical Patients" data-table = "patients">See Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Patients -->
            <div class="current-patients">
                <h5 class="mb-4">Current Patients</h5>
                <div class="patient-list">
                    <div class="mb-4" id="patientList"></div>
                </div>
            </div>
        </div>
    </div>

   
     
</div>

 <!-- Modal See Details -->
<div id="modal" class="fixed inset-0 flex items-center justify-center z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-md shadow-lg max-w-4xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
        <!-- Data will be inserted her by JS -->
            <h2 id="modal-title" class="text-2xl font-bold">  </h2>
            <button class="text-gray-500 hover:text-gray-700" id="close">
                Close
            </button>
        </div>
        <div id="modal-content" class="space-y-6"></div>
    </div>
</div>

<!-- Modal for complete confirmation -->
<div class="modal" id="confirmModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Confirm Update</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <p>Are you sure you want to mark this appointment as completed?</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-custom">Confirm</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal to open patient information -->
<?php include "../../templates/patientInfo.php"; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../../assets/js/doctor-dashboard.js"></script>
    <script src="../../../assets/js/patientInfo-modal.js"></script>
    <!-- <script src="../../assets/js/admin-modal.js"></script> -->
</body>
</html>