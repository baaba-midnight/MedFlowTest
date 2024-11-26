$(document).ready(function() {
    $('#myForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        
        $('#alert-container').empty(); // Clear previous alerts
    
        // Get form values
        const fname = $('#fname').val().trim();
        console.log(fname);
        const mname = $('#mname').val().trim();
        const lname = $('#lname').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();

        let isValid = true;
    
        // Username validation
        if (fname === '') {
            showAlert("First name cannot be empty.");
            isValid = false;
            return;
        }

        if (mname === '') {
            showAlert("Middle name cannot be empty.");
            isValid = false;
            return;
        }

        if (lname === '') {
            showAlert("Last name cannot be empty.");
            isValid = false;
            return;
        }


        if (email === '') {
            showAlert("Enter an email.");
            isValid = false;
            return;
        }

        if (!validateEmail(email)) {
            showAlert("Enter a valid email");
            isValid = false;
            return;
        }

        if (password === '') {
            showAlert("Enter a password");
            isValid = false;
            return;
        }

        if (!validatePassword(password)) {
            showAlert("Your password must be at least 8 characters long and include at least one uppercase letter (A-Z), one lowercase letter (a-z), one number (0-9), and one special character (e.g., @, $, !, %, *, ?, &). Make sure to avoid using spaces or unsupported characters");
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


    function validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    function validatePassword(password) {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
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