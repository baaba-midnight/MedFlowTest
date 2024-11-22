<div class="modal fade" id="displayModal" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Patient Information</h2>
                    <span class="status-badge" id="statusInfo">Inpatient</span>
                </div>

                <!-- Basic Information -->
                <div class="card">
                    <h4 class="mb-4">Basic Information</h4>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="info-label">Patient ID</div>
                                    <div class="info-value" id="patientID">P001</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value" id="patientName">John Doe</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Age</div>
                                    <div class="info-value" id="patientAge">45 years</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Gender</div>
                                    <div class="info-value" id="patientGender">Male</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Admission Date</div>
                                    <div class="info-value" id="patientAdmissionDate">2023-10-15</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card">
                    <h4 class="sm-4">Contact Information</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-label">Phone</div>
                            <div class="info-value" id="patientContact">+1 (555) 123-4567</div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-label">Address</div>
                            <div class="info-value" id="patientAddress">123 Medical Centre Drive, Healthcare City, HC 12345</div>
                        </div>
                    </div>
                </div>

                <!-- Patient Notes -->
                <div class="card">
                    <h4 class="sm-4">Patient Notes</h4>

                    <textarea class="form-control info-value" id="patientNotes" readonly></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>