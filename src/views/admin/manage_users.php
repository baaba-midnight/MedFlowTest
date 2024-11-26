<?php include "adminSession.inc.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- For icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/edit.css">
    <link rel="stylesheet" href="../../../assets/css/modalDisplayInfo.css">
    <link rel="stylesheet" href="../../../assets/css/admin-tables.css">

    <!-- Load JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include '../../templates/admin-sidebar.php'; ?>

    <div class="main-content">
        <?php 
            $headerTitle = 'Manage Users';
            $buttonContent = 'Add User';
            include '../../templates/header.php'; 
        ?>

        <div class="table-container">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    <!-- Insert data with JS -->
                </tbody>
            </table>
        </div>
    </div>
        <!-- Open Modal -->
        <div class="modal fade" id="userDetails" data-bs-backdrop="true" data-bs-keyboard="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4" style="font-family: 'Roboto', sans-serif;">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="mb-0">User Details</h2>
                        </div>

                        <!-- Modal Content -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="info-label">User ID</div>
                                <div class="info-value" id="userID">U001</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">Full Name</div>
                                <div class="info-value" id="userName">John Doe</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">Email</div>
                                <div class="info-value" id="userEmail">john.doe@medflow.com</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">Role</div>
                                <div class="info-value" id="role">Doctor</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <?php include "../../templates/delete.php" ?>

        <!-- Edit Modal for Users -->
        <div class="modal fade" id="editUserModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body ms-3" style="font-family:'Roboto', sans-serif;">
                        
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <img src="../../../assets/images/medflow-logo.png" width="200" height="100" alt="MedFlow-logo">
                        </div>

                        <h4 class="modal-title mt-3 mb-2"><b>Edit User Details</b></h4>
                        <div id="alert-container"></div>

                        <form method="POST" id="myForm" action="../../functions/admin/updateUser.inc.php">
                            <input type="hidden" value="updateUser" name="type">
                            <div class="row mt-4">
                                <div class="col">
                                    <label for="fname" class="form-label"><b>First Name*</b></label>
                                    <input type="text" id="fname" class="form-control" placeholder="Enter first name" name="fname" >
                                </div>
                                <div class="col">
                                    <label for="mname" class="form-label"><b>Middle Name</b></label>
                                    <input type="text" id="mname" class="form-control" placeholder="Enter middle name" name="mname">
                                </div>
                                <div class="col">
                                    <label for="lname" class="form-label"><b>Last Name*</b></label>
                                    <input type="text" id="lname" class="form-control" placeholder="Enter last name" name="lname" >
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col">
                                    <label for="email" class="form-label"><b>Email*</b></label>
                                    <input type="email" id="email" class="form-control" placeholder="Enter Email" name="email" >
                                </div>

                                <div class="col">
                                    <label for="password" class="form-label"><b>Password*</b></label>
                                    <input type="text" id="password" class="form-control" placeholder="Enter Password" name="password">
                                </div>

                                <div class="col">
                                    <label for="role" class="form-label"><b>Role*</b></label>
                                    <select id="role" name="role" class="form-select" required>
                                        <option value="">Select User Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="doctor">Doctor</option>
                                        <option value="nurse">Nurse</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div style="margin-top: 10px;">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-custom" id="edit" type="submit">Save Changes</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- Add New User Modal -->
    <div class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body ms-3" style="font-family:'Roboto', sans-serif;">
                    
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <img src="../../../assets/images/medflow-logo.png" width="200" height="100" alt="MedFlow-logo">
                    </div>

                    <h4 class="modal-title mt-3 mb-2"><b>Add New User</b></h4>
                    <div id="alert-container"></div>

                    <form id="addUserForm">
                        <div class="row mt-4">
                            <div class="col">
                                <label for="fname" class="form-label"><b>First Name*</b></label>
                                <input type="text" id="user-fname" class="form-control" placeholder="Enter first name" name="fname" required>
                            </div>
                            <div class="col-4">
                                <label for="mname" class="form-label"><b>Middle Name</b></label>
                                <input type="text" id="user-mname" class="form-control" placeholder="Enter middle name" name="mname">
                            </div>
                            <div class="col-4">
                                <label for="lname" class="form-label"><b>Last Name*</b></label>
                                <input type="text" id="user-lname" class="form-control" placeholder="Enter last name" name="lname" required>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-4">
                                <label for="email" class="form-label"><b>Email*</b></label>
                                <input type="email" id="user-email" class="form-control" placeholder="Enter last name" name="email" required>
                            </div>

                            <div class="col-4">
                                <label for="password" class="form-label"><b>Password*</b></label>
                                <input type="password" id="user-password" class="form-control" placeholder="Enter Password" name="password" required aria-describedby="passwordToggle">
                            </div>

                            <div class="col-4">
                                <label for="confirmPassword" class="form-label"><b>Confirm Password*</b></label>
                                <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" name="confirmPassword" required aria-describedby="passwordToggle">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-4">
                                <label for="role" class="form-label"><b>Role*</b></label>
                                <select id="role" name="role" class="form-select" required>
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="nurse">Nurse</option>
                                </select>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div style="margin-top: 10px;">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-custom" id="add" type="submit" data-bs-dismiss="modal">Add New User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/users/fetch_users.js"></script>
    <script src="../../../assets/js/users/fetch_user.js"></script>
    <script src="../../../assets/js/delete_user.js"></script>
    <script src="../../../assets/js/edit_user_modal.js"></script>

    <script src="../../../assets/js/users/userInfo-modal.js"></script>
    <script src="../../../assets/js/users/addUser.js"></script>
    <sctip src="../../../assets/js/users/updateUser.js"></sctip>
</body>
</html>