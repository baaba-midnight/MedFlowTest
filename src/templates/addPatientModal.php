<!-- Form Modal to ADD patient data -->
<div class="modal fade" id="addPatientModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
    
            <!-- Modal body -->
            <div class="modal-body ms-3"  style="font-family:'Roboto', sans-serif;">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <img src="../../assets/images/medflow-logo.png" widtth="200" height="100" alt="MedFlow-logo">
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
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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
                            <select id="doctorDropdown" name="doctorDropdown" class="form-select" required>
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

                        <div class="col">
                            <label for="is_critical" class="form-label"><b>Is critical?*</b></label>
                            <select id="is_critical" name="is_critical" class="form-select" required>
                                <option value="">Select Critical Status</option>
                                <option value="1">True</option>
                                <option value="0">False</option>
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
                        <button type="submit" class="btn btn-custom" data-bs-dismiss="modal">Add Patient</button>
                    </div>
                </form>
            </div>
    
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('../../functions/get_doctors.php')
            .then(response => response.json())
            .then(doctors => {
                const dropdown = document.getElementById('doctorDropdown');
                doctors.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = `Dr. ${doctor.fullName}`;
                    dropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching doctors:', error));

            // Handle Form Submission in patients.inc.php
    })
</script>