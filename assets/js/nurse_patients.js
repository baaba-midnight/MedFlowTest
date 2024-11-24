// Fetch the JSON data from the PHP script
fetch('../../functions/nurse_patients.inc.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => {
    return response.json()
})
.then(data => {
    // Check if data is an array (contains patients) or if it's an error message
    if (data.status === "success" && data.data) {
        // Populate the table with the patient data
        const tableBody = document.getElementById('patientTable').querySelector('tbody');
        
        data.data.forEach(patient => {
            const row = document.createElement('tr');

            row.setAttribute('data-id', patient["id"]);
            
            row.innerHTML = `
                <td>${patient['full_name']}</td>
                <td>${patient["doctor_name"]}</td>
                <td><div class="status ${patient["status"]}">${patient["status"]}</div></td>
                <td>${patient["updated_at"]}</td>
                <td>
                    <div class="selected-actions" id="selectedActions">
                        <button type="button" class="action-btn edit-btn" data-id="${patient["id"]}">
                            <span class="action-icon">✏️</span> Edit
                        </button>
                        <button class="generate-report action-btn" data-id="${patient["id"]}">
                            <span class="action-icon">📄</span> Report
                        </button>
                        <button class="action-btn open-btn" data-bs-toggle="modal" data-bs-target="#displayModal" data-id="${patient["id"]}">
                            <span class="action-icon">📂</span> Open
                        </button>
                        <button class="action-btn" data-bs-toggle="modal" data-bs-target="#addAppointmentModal" data-id="${patient["id"]}">
                            <span class="action-icon">🗓️</span> Add Appointment
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);

        });
    } else {
        // Handle case where no patients are available
        alert(data.message || "No patients data available.");
    }
})
.catch(error => console.error('Error fetching patient data:', error));
