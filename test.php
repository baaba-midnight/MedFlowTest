<div class="modal-body">
    <div class="form-group">
        <label for="patientNotes">Patient Notes</label>
        <div class="input-group">
            <textarea 
                id="patientNotes" 
                class="form-control" 
                rows="4" 
                readonly
            ></textarea>
            <div class="input-group-append">
                <button 
                    id="editNotesBtn" 
                    class="btn btn-outline-secondary" 
                    type="button"
                >
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>
        </div>
        <form id="notesUpdateForm" style="display:none;">
            <textarea 
                id="editableNotes" 
                class="form-control mt-2" 
                rows="4"
            ></textarea>
            <div class="mt-2">
                <button 
                    type="submit" 
                    class="btn btn-primary mr-2"
                >Save Changes</button>
                <button 
                    type="button" 
                    id="cancelEditBtn" 
                    class="btn btn-secondary"
                >Cancel</button>
            </div>
        </form>
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
        
        try {
            const response = await fetch('../../functions/patients.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'updatePatientNotes',
                    patientId: document.getElementById('patientID').textContent,
                    notes: editableNotes.value
                })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            
            // Update readonly textarea with new notes
            notesTextarea.value = editableNotes.value;
            
            // Hide editable form, show readonly textarea
            notesUpdateForm.style.display = 'none';
            notesTextarea.style.display = 'block';
            editNotesBtn.style.display = 'block';

            // Optional: Show success message
            alert('Notes updated successfully');

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