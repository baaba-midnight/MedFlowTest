document.addEventListener('DOMContentLoaded', () => {
    const confirmInput = document.getElementById('confirmDeleteInput');
    const confirmButton = document.getElementById('confirmDeleteButton');
    let userIdToDelete;

    // Get patient ID when modal opens
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-btn')) {
            userIdToDelete = event.target.getAttribute('data-id');
            console.log("User ID to delete:", userIdToDelete);
        }
    });
    

    // Enable the delete button only when "delete" is typed
    confirmInput.addEventListener('input', () => {
        confirmButton.disabled = confirmInput.value.trim().toUpperCase() !== 'DELETE';
    });
    
    // Handle delete confirmation logic
    confirmButton.addEventListener('click', () => {
        if (confirmInput.value.trim().toUpperCase() === "DELETE") {
            // send AJAX request to delete patient
            $.ajax({
                url: '../../functions/users.inc.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    type: 'deleteUser',
                    id: userIdToDelete
                }),
                success: function(response) {
                    if (response.status === "success") {
                        // remove the row from the table
                        $(`tr[data-id="${parseInt(userIdToDelete)}"]`).remove();
                        alert(response.data.message);
                    } else {
                        alert("Error deleting patient");
                    }
                },
                error: function() {
                    alert("An error occurred while deleting the patient");
                }
            });
        }

        // Reset input and close the modal
        confirmInput.value = '';
        confirmButton.disabled = true;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
        modal.hide();
    });
});