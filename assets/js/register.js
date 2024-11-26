$(document).ready(function() {
    $('#myForm').on('submit', function(event) {
        console.log("Form submitting")
        event.preventDefault(); // Prevent the default form submission
        $('#alert-container').empty(); // Clear previous alerts
    
        // Get form values
        const fname = $('#fname').val().trim();
        const mname = $('#mname').val().trim();
        const lname = $('#lname').val().trim();
        const dob = $('#dob').val().trim();
        const role = $('#role-options').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const confirm_password = $('#password2').val().trim();


        let isValid = true;
    
        // Username validation
        if (fname === '') {
            showAlert("First name cannot be empty.");
            return;
        }

        if (mname === '') {
            showAlert("Middle name cannot be empty.");
            return;
        }

        if (lname === '') {
            showAlert("Last name cannot be empty.");
            return;
        }

        if (dob === '') {
            showAlert("Pick date of birth.");
            return;
        }

        if (!validateDOB(dob)) {
            showAlert("Please enter a valid date of birth.");
            return;
        }


        if (email === '') {
            showAlert("Email cannot be empty.");
            return;
            console.log("AMa")
        }

        if (!validateEmail(email)) {
            showAlert("Please enter a valid email address.");
            return;
        }

        if (role === '') {
            showAlert("Choose a role.");
            return;
        }


        if (password === '') {
            showAlert("Enter a password.");
            return;
        }

        if (!validatePassword(password)) {
            showAlert("Your password must be at least 8 characters long and include at least one uppercase letter (A-Z), one lowercase letter (a-z), one number (0-9), and one special character (e.g., @, $, !, %, *, ?, &). Make sure to avoid using spaces or unsupported characters.");
            return;
        }

        if (confirm_password === '') {
            showAlert("Confirm your password.");
            return;
        }

        if (password != confirm_password) {
            showAlert("Password mismatch!");
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


    

    $('#register').click(function() {
        console.log("Button Clicked")
        $('#myForm').submit();
    });

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validatePassword(password) {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    }

    function validateDOB(dob) {
        const dateRegex = /^\d{4}-([0]?[1-9]|1[0-2])-([0]?[1-9]|[12]\d|3[01])$/;
        return dateRegex.test(dob);
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