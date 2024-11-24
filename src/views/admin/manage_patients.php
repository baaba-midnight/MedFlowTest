<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- For icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/edit.css">
    <link rel="stylesheet" href="../../../assets/css/modalDisplayInfo.css">
    <link rel="stylesheet" href="../../../assets/css/admin-tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include '../../templates/admin-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php 
            $headerTitle = 'Manage Patients';
            $buttonContent = 'Add Patient';
            include '../../templates/header.php'; 
        ?>

        <div class="table-container">
            <table class="table" id="patientTable">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Admission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                  <!-- Data will be inserted here by JS -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Display Patient Information Modal -->
    <?php include '../../templates/patientInfo.php' ?>

    <!-- Delete Modal -->
    <?php include "../../templates/delete.php" ?>

    <!-- Patient Form Modal -->
    <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
    
            <!-- Modal body -->
            <div class="modal-body ms-3"  style="font-family:'Roboto', sans-serif;">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <img src="../../../assets/images/medflow-logo.png" widtth="200" height="100" alt="MedFlow-logo">
                </div>
                
                <h4 class="modal-title mt-3 mb-2"><b>Add New Patient</b></h4>
                <div id="alert-container"></div>
                <form id="addPatientForm">
                    <div class="row mt-4">
                        <div class="col">
                        <label for="fname" class="form-label"><b>First Name*</b></label>
                        <input type="text" id="fname" class="form-control" placeholder="Enter first name" name="fname" required>
                        </div>
                        <div class="col">
                        <label for="mname" class="form-label"><b>Middle Name</b></label>
                        <input type="text" id="mname" class="form-control" placeholder="Enter middle name" name="mname">
                        </div>
                        <div class="col">
                        <label for="lname" class="form-label"><b>Last Name*</b></label>
                        <input type="text" id="lname" class="form-control" placeholder="Enter last name" name="lname" required>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <label for="dob" class="form-label"><b>Date of Birth*</b></label>
                            <input type="date" id="dob" class="form-control" placeholder="mm/dd/yyyy" name="dob" required>
                        </div>
                        <div class="col">
                            <label for="gender" class="form-label"><b>Gender*</b></label>
                            <select id="gender" name="gender" class="form-select">
                                <option value="male">male</option>
                                <option value="female">female</option>
                            </select>
                        </div>
                        
                        <div class="col">
                            <label for="phone" class="form-label"><b>Phone Number*</b></label>
                            <input type="tel" id="phone" class="form-control" placeholder="Enter phone number. E.g +123456789" name="phone" required>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <label for="doctorDropdown" class="form-label"><b>Assign Doctor*</b></label>
                            <select id="doctorDropdown" name="doctorDropdown" class="form-select" disabled>
                                <option value="">Select a Doctor</option>
                                <!-- Doctors will be dynamically populated here -->
                            </select>
                        </div>

                        <div class="col">
                            <label for="status" class="form-label"><b>Patient Status*</b></label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select Patient Status</option>
                                <option value="inpatient">Inpatient</option>
                                <option value="outpatient">Outpatient</option>
                                <option value="discharged">Discharged</option>
                            </select>
                        </div>
                    </div>

                    <label for="address" class="form-label mt-4"><b>Address*</b></label>
                    <textarea class="form-control" id="address" name="address" rows="5" maxlength="500" placeholder="Enter your address" required></textarea>

                    <label for="notes" class="form-label mt-4"><b>Notes</b></label>
                    <textarea class="form-control" id="notes" rows="5" name="notes" placeholder="Take Patient Notes"></textarea>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom" id="edit">Add Patient</button>
                    </div>
                </form>
            </div>
    
        </div>
    </div>
</div>


	<!-- Add Modal Inclusion -->
    <?php 
      $modalTitle = 'Add New Patient';
      $saveButton = 'Add Patient';
      include '../../templates/addPatientModal.php';
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/fetch_patients.js"></script>
    <script src="../../assets/js/edit_patient_modal.js"></script>
    <script src="../../../assets/js/fetch_patient.js"></script>
    <script src="../../../assets/js/patientInfo-modal.js"></script>
    <script src="../../../assets/js/delete_patient.js"></script>
    <script src="../../../assets/js/addPatient.js"></script>
</body>
</html>
