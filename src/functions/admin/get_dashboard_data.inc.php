<?php
require_once '../../includes/config.inc.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $data = [];

        // get patiet status distribution
        $query = "SELECT `status`, COUNT(*) as count FROM Patients GROUP BY `status`";
        $stmt = $conn->prepare($query);
        $patientStatus = [];
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $patientStatus[] = [
                    'name' => $row['status'],
                    'value' => (int)$row['count']
                ];
            }

            $data['patientStatus'] = $patientStatus;
        }

        // get weekly admissions
        $query = "SELECT 
                    DATE(admission_date) as admission_day,
                    COUNT(*) as admission_count
                FROM patients
                WHERE admission_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                GROUP BY DATE(admission_date)
                ORDER BY admission_day";

        $stmt = $conn->prepare($query);
        $weeklyAdmissions = [];

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $weeklyAdmissions[] = [
                    'date' => $row['admission_day'],
                    'count' => (int)$row['admission_count']
                ];
            }

            $data['weeklyAdmissions'] = $weeklyAdmissions;
        }

        // Get Quick Stats
        $query = "SELECT
                    (SELECT COUNT(*) FROM patients WHERE `status` = 'inpatient' OR `status` = 'outpatient') as active_patients,
                    (SELECT COUNT(*) FROM users) as total_staff,
                    (SELECT COUNT(*) FROM appointments WHERE `status` = 'in_progress') as pending_appointments,
                    (SELECT COUNT(*) FROM patients WHERE is_critical = TRUE) as critical_patients";
        
        $stmt = $conn->prepare($query);
        $quickStats = [];

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $quickStats[] = [
                    'criticalPatients' => (int)$row['critical_patients'],
                    'activePatients' => (int)$row['active_patients'],
                    'todayAppointments' => (int)$row['pending_appointments'],
                    'totalStaff' => (int)$row['total_staff']
                ];
            }

            $data['quickStats'] = $quickStats;
        }

        // return JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}