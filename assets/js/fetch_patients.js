function calculateAge(birthDateString) {
    const birthDate = new Date(birthDateString);
    const today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    const dayDifference = today.getDay() - birthDate.getDay();

    if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
        age--;
    }
    return age;
}

// Fetch the JSON data from the PHP script
fetch('../../functions/patients.inc.php?type=getPatients')
.then(response => response.json())
.then(data => {
    // Check if data is an array (contains patients) or if it's an error message
    if (Array.isArray(data)) {
        // Populate the table with the patient data
        const tableBody = document.getElementById('patientTable').querySelector('tbody');
        const modals = document.getElementById('modals')
        data.forEach(patient => {
            const row = document.createElement('tr');
            const modal = document.createElement('div');


            row.setAttribute('data-id', patient["id"]);
            // console.log(patient["Patient ID"]);
            date_of_birth = toString(patient["date_of_birth"]);
            console.log(date_of_birth)
            age = calculateAge(date_of_birth);

            console.log(age);
            

            row.innerHTML = `
                <td>${patient["id"]}</td>
                <td>${patient['name']}</td>
                <td>${patient['age'] !== null ? patient['age'] : 'N/A'}</td>
                <td>${patient["gender"]}</td>
                <td>${patient["admission_date"]}</td>
                <td><div class="status ${patient["status"]}">${patient["status"]}</div></td>
                <td>
                    <div class="selected-actions" id="selectedActions">
                        <button type="button" class="action-btn edit-btn" data-id="${patient["id"]}">
                            <span class="action-icon">✏️</span> Edit
                        </button>
                        <button class="action-btn remove-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-id="${patient["id"]}">
                            <span class="action-icon">🗑️</span> Remove
                        </button>
                        <button class="action-btn open-btn" data-bs-toggle="modal" data-bs-target="#displayModal" data-id="${patient["id"]}">
                            <span class="action-icon">📂</span> Open
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