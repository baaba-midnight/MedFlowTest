<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Roboto Font Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../../assets/css/register.css">
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="main-box">

        <div class="image-container">
            <img src="../../assets/images/doctors.jpg" alt="doctors">
        </div>
        
        <div class="form-container">
            <div class="logo-home">
                <a href="../../index.php" >
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="logo ms-5" >
                            <img class="ms-5" src="../../assets/images/medflow-logo.png" widtth="300" height="200" alt="MedFlow-logo">
                        </div>
                    </div>
                    
                </a>
            </div>

            <h6>Medical Staff Registration</h6>
            <p>Please fill in your information to create an account</p>
            <div id="alert-container">
            </div>
            <form id="myForm" action="../functions/registerUser.inc.php" method="POST">
                <!-- Personal Information -->
                <div>
                    <h6>Personal Information</h6>
                    <div class="form-box">
                        <label>First Name *</label>
                        <input type="text" placeholder="Enter first name" name="fname" id="fname" >

                        <label>Middle Name *</label>
                        <input type="text" placeholder="Enter middle name" name="mname" id="mname" >

                        <label>Last Name *</label>
                        <input type="text" placeholder="Enter last name" name="lname" id="lname">
                    </div>
                </div>

                <!-- Account Information -->
                <div>
                    <h6>Account Information</h6>
                    <div class="form-box">
                        <label>Email Address *</label>
                        <input type="text" placeholder="Enter email address" name="email" id="email">

                        <label>Role *</label>
                        <select name="role-options" id="role-options" class="form-select">
                            <option value="" default>Select Role</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                        </select>

                        <label>Password *</label>
                        <input placeholder="Enter password" type="password" name="password" id="password">

                        <label>Confirm Password *</label>
                        <input placeholder="Confirm Password" type="password" name = "password2" id="password2">    
                    </div>
                </div>

                <div class="auth-links">
                    <span>Already registered?</span>
                    <a href="login.php" class="login-link">Log In</a>
                </div>

                <button id="register" class="btn mt-4">Register</button>
            </form>
                
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/register.js"></script>
</body>
</html>