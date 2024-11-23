<?php
$userId = 2;
$first_name = "Michael";
$last_name = "Jerome";

$fullName = $first_name . " " . $last_name;
$email = "michael.jerome@medflow.com";
$role = 'Admin';
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo-section">
        <img src="../../../assets/images/medflow_mini_logo.png" alt="MedFlow">
        <h1>MedFlow</h1>
    </div>
    
    <nav class="nav flex-column mt-4">
        <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a class="nav-link" href="manage_patients.php"><i class="fas fa-user-injured me-2"></i> Patient Management</a>
        <a class="nav-link" href="manage_users.php"><i class="fas fa-user me-2"></i> User Management</a>
    </nav>

    <div class="user-profile">
        <div class="profile-image">
            <img src="../../../assets/images/man-1.jpg" alt="Profile" class="me-3">
        </div>
        <div class="profile-details">
            <h6><?php echo $fullName ?></h6>
            <p><?php echo $role ?></p>
            <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
        </div>
    </div>
</div>

<script>
    // Get the current page url
    const currentPage = window.location.pathname.split("/").pop();
    console.log(currentPage);

    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll(".nav-link");


    // add the active class to the current page link
    sidebarLinks.forEach(link => {
        console.log(link.getAttribute('href'))
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    })
</script>