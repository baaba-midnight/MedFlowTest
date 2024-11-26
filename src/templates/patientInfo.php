<?php
if ($role !== 'doctor') {
    $settings = 'default';
} else {
    $settings = 'custom';
}
?>

<div class="modal fade" id="displayModal" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-4" style="font-family: 'Roboto', sans-serif;">
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
                    <?php if ($settings === "default"): ?>
                        <textarea class="form-control info-value" id="patientNotes" readonly></textarea>
                    <?php elseif ($settings === "custom"): ?>
                        <div class="input-group">
                            <textarea id="patientNotes" class="form-control" rows="4" readonly></textarea>
                            <div class="input-group-append">
                                <button id="editNotesBtn" class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                        <form id="notesUpdateForm" style="display:none;">
                            <textarea id="editableNotes" class="form-control mt-2" rows="4"></textarea>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                                <button type="button" id="cancelEditBtn" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notesTextarea = document.getElementById('patientNotes');
    const editNotesBtn = document.getElementById('editNotesBtn');
    const notesUpdateForm = document.getElementById('notesUpdateForm');
    const editableNotes = document.getElementById('editableNotes');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    // Edit button click handler
    editNotesBtn.addEventListener('click', function() {
        // Populate editable textarea with current notes
        editableNotes.value = notesTextarea.value;
        
        // Hide readonly textarea and edit button
        notesTextarea.style.display = 'none';
        editNotesBtn.style.display = 'none';
        
        // Show editable form
        notesUpdateForm.style.display = 'block';
    });

    // Cancel button handler
    cancelEditBtn.addEventListener('click', function() {
        // Hide editable form
        notesUpdateForm.style.display = 'none';
        
        // Show readonly textarea and edit button
        notesTextarea.style.display = 'block';
        editNotesBtn.style.display = 'block';
    });

    // Form submission handler
    notesUpdateForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // console.log(JSON.stringify({
        //             type: 'updatePatientNotes',
        //             id: document.getElementById('patientID').textContent,
        //             notes: editableNotes.value
        //         }));

        console.log(editableNotes.value);

        try {
            const response = await fetch('../../functions/patients.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'updatePatientNotes',
                    id: document.getElementById('patientID').textContent,
                    notes: editableNotes.value
                })
            });

            const result = await response.json();
            if (response.ok) {
                if (result.status === 'success') {
                    // Update readonly textarea with new notes
                    notesTextarea.value = editableNotes.value;
                    
                    // Hide editable form, show readonly textarea
                    notesUpdateForm.style.display = 'none';
                    notesTextarea.style.display = 'block';
                    editNotesBtn.style.display = 'block';

                    // Optional: Show success message
                    alert(result.data.message);
                }
            } else {
                console.error('Error updating notes:', result.data.message);
                alert('Failed to updated notes. Please try agian.');
            }
        } catch (error) {
            console.error('Error updating notes:', error);
            alert('Failed to update notes. Please try again.');
        }
    });
});

// Modify populateModal function to set initial notes
function populateModal(data) {
    // ... other existing code ...
    
    // Set readonly textarea value
    document.getElementById('patientNotes').value = data['notes'] || 'No notes available';
    
    // Reset form to initial state
    document.getElementById('notesUpdateForm').style.display = 'none';
    document.getElementById('patientNotes').style.display = 'block';
    document.getElementById('editNotesBtn').style.display = 'block';
}
</script>