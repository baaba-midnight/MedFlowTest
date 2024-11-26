$(document).ready(function() {
    $('#myForm').on('submit', function(event) {
        console.log("Form submitting")
        event.preventDefault(); // Prevent the default form submission
        $('#alert-container').empty(); // Clear previous alerts
    
        // Get form values
        const fname = $('#fname').val().trim();
        console.log(fname)
        const mname = $('#mname').val().trim();
        const lname = $('#lname').val().trim();
        const dob = $('#dob').val().trim();
        const gender = $('#gender').val().trim(); 
        const phone = $('#phone').val().trim();
        const doctor = $('#doctorDropdown').val();
        const status = $('#status').val().trim();
        const address = $('#address').val().trim();
        const is_critical = $('#is_critical').val();

        let isValid = true;
    
        // Username validation
        if (fname === '') {
            showAlert("First name cannot be empty.");
            isValid = false;
            return;
        }

        if (lname === '') {
            showAlert("Last name cannot be empty.");
            isValid = false;
            return;
        }

        if (dob === '') {
            showAlert("Pick Patient's date of birth.");
            isValid = false;
            return;
        }

        if (!validateDOB(dob)) {
            showAlert("Please enter a valid date of birth.");
            isValid = false;
            return;
        }

        if (gender === '') {
            showAlert("Select Patient's gender.");
            isValid = false;
            return;
        }
        
        if (phone === '') {
            showAlert("Enter a phone number");
            isValid = false;
            return;
        }

        if (!validatePhone(phone)) {
            showAlert("Please enter a valid phone number. Begin with an the country code.");
            isValid = false;
            return;
        }

        if (status === '') {
            showAlert("Status cannot be empty.");
            isValid = false;
            return;
        } 

        if (is_critical === '') {
            showAlert("Is patient critical or not?");
            isValid = false;
            return;
        }
        

        if (address === '') {
            showAlert("Enter an address");
            isValid = false;
            return;
        }


        

        

        if (isValid) {
            $('#alert-container').append(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Form submitted successfully!
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              `);
            this.submit();
        }

    });

    // $('#edit').click(function() {
    //     console.log("Button Clicked")
    //     $('#myForm').submit();
    // });


    function validateDOB(dob) {
        const dateRegex = /^\d{4}-([0]?[1-9]|1[0-2])-([0]?[1-9]|[12]\d|3[01])$/;
        return dateRegex.test(dob);
    }

    function validatePhone(phone) {
        const dateRegex = /^\+?[1-9]\d{1,14}$/;
        return dateRegex.test(phone);
    }

    function showAlert(message) {
        $('#alert-container').append(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        $('.form-container').scrollTop(0);

        isValid = false;
    }

    

   
});