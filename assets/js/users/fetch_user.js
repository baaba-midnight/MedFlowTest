$(document).on('click', '.edit-btn', function() {
    // When any "Open" button is clicked
        // Get the patient ID from the button's data-id attribute
        console.log("Button clicked");
    //   $('#myModal').modal('show');
        const userId = $(this).data('id');
    
        // Send an AJAX request to fetch patient data
        $.ajax({
            url: '../../functions/admin/fill_users_modal.php?id=' + userId,  // Replace with your actual API endpoint
            method: 'GET',
            success: function(user) {
                // console.log(patient.gender)
                // console.log(patient.doctor_name)
                // Populate the modal with the fetched patient data
                $('#fname').val( user.first_name);
                $('#mname').val(user.middle_name);
                $('#lname').val(user.last_name);
                $('#email').val(user.email);
                $('#password').val(user.password);
                
                // Show the modal
                $('#editUserModal').modal('show');
            },
            error: function(xhr, status, error) {
                // Handle any errors here (e.g., show an error message)
                alert('Error fetching patient data: ' + error);
            }
        });
    });

    