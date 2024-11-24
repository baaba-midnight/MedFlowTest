<!-- PHP code for fetching data -->
<?php
include "src/includes/config.inc.php";

// Get patients
function getPatients() {
    global $conn;
    $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as patient_name 
            FROM patients 
            ORDER BY first_name";

    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get doctors
function getDoctors() {
    global $conn;
    $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as doctor_name
            FROM doctors 
            ORDER BY first_name";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- HTML Modal -->
<div class="modal fade" id="assignDoctorModal" tabindex="-1" aria-labelledby="assignDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignDoctorModalLabel">Assign Doctor to Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignDoctorForm">
                    <div class="mb-3">
                        <label for="patientSelect" class="form-label">Select Patient</label>
                        <select class="form-select" id="patientSelect" name="patient_id" required>
                            <option value="">Search for a patient...</option>
                            <?php
                            $patients = getPatients();
                            foreach($patients as $patient) {
                                echo "<option value='" . $patient['id'] . "'>" . 
                                     htmlspecialchars($patient['patient_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="doctorSelect" class="form-label">Assign Doctor</label>
                        <select class="form-select" id="doctorSelect" name="doctor_id" required>
                            <option value="">Search for a doctor...</option>
                            <?php
                            $doctors = getDoctors();
                            foreach($doctors as $doctor) {
                                echo "<option value='" . $doctor['id'] . "'>" . 
                                     htmlspecialchars($doctor['doctor_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveAssignment">Save Assignment</button>
            </div>
        </div>
    </div>
</div>

<!-- Required CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

<!-- Required JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- JavaScript for initialization and handling -->
<script>
$(document).ready(function() {
    // Initialize Select2 for both dropdowns
    $('#patientSelect').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#assignDoctorModal'),
        placeholder: 'Search for a patient...',
        allowClear: true
    });

    $('#doctorSelect').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#assignDoctorModal'),
        placeholder: 'Search for a doctor...',
        allowClear: true
    });

    // Handle form submission
    $('#saveAssignment').click(function() {
        const patientId = $('#patientSelect').val();
        const doctorId = $('#doctorSelect').val();

        if (!patientId || !doctorId) {
            alert('Please select both a patient and a doctor');
            return;
        }

        // Send assignment to server
        $.ajax({
            url: 'assign_doctor.php',
            method: 'POST',
            data: {
                patient_id: patientId,
                doctor_id: doctorId
            },
            success: function(response) {
                if (response.success) {
                    $('#assignDoctorModal').modal('hide');
                    alert('Assignment saved successfully');
                    // Optionally refresh the page or update UI
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while saving the assignment');
            }
        });
    });
});
</script>