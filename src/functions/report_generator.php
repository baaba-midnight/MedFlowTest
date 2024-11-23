<?php
// Include autoloader
require_once '../includes/config.inc.php';
require_once '../../vendor/autoload.php';

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // query to get patient details
    $sql = "SELECT 
            first_name,
            middle_name,
            last_name, 
            date_of_birth, 
            gender, 
            admission_date, 
            phone, 
            `address`,
            notes
            FROM patients
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();

        // get patient data
        $name = trim(($patient['first_name'] ?? '') . ' ' . ($patient['middle_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $dob = $patient['date_of_birth'];
        $gender = $patient['gender'];
        $admission_date = $patient['admission_date'];
        $phone = $patient['phone'];
        $address = $patient['address'];
        $notes = $patient['notes'];
    } else {
        die("No patient found with the provided ID");
    }

    $stmt->close();
    $conn->close();

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('MedFlow.admin.docs');
    $pdf->SetAuthor('Nurse');
    $pdf->SetTitle('Patient Report');

    // Set margins
    $pdf->SetMargins(15, 15, 15);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add content
    $html = " 
    <h1> Basic Information</h1>
    <table border='1' cellpadding='5'>
        <tr>
            <th>Name</th>
            <td>$name</td>
        </tr>

        <tr>
            <th>Date of Birth</th>
            <td>$dob</td>
        </tr>

        <tr>
            <th>Admission Date</th>
            <td>$admission_date</td>
        </tr>

        <tr>
            <th>Phone</th>
            <td>$phone</td>
        </tr>

        <tr>
            <th>Address</th>
            <td>$address</td>
        </tr>


    <div>
        <h4>Patient Notes</h4>
        <p>$notes</p>
    </div>";

    // Print content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output PDF
    $pdf->Output("patient_report_$patient_id.pdf", 'D'); // 'D' means download
} else {
    echo "No patient ID provided.";
}
?>