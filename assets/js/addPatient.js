document.addEventListener('DOMContentLoaded', () => {
  const formElement = document.getElementById('addPatientForm');

  if (formElement) {
	formElement.addEventListener('submit', async function submitPatientForm(event) {
	  event.preventDefault();

	  const formData = new FormData(event.target);
	  const patientData = Object.fromEntries(formData);

	  // Validate input fields
	  if (!patientData.fname || !patientData.lname || !patientData.dob) {
		alert("Please fill all required fields");
		return;
	  }

	  const type = 'addPatient';
	  console.log(patientData.fname);

	  console.log(JSON.stringify({type, ...patientData}));

	  try {
		const response = await fetch('../../functions/patients.inc.php', {
		  method: 'POST',
		  headers: {
			'Content-Type': 'application/json',
		  },
		  body: JSON.stringify({ type, ...patientData }),
		});

		const result = await response.json();
		console.log(result);
		if (result.data.message === 'Patient added successfully') {
			// refresh the table content
			loadPatients();

			alert(result.data.message);
			event.target.reset();
		} else {
		  	alert("Error adding patient");
		}
	  } catch (error) {
		console.error('Error:', error);
	  }
	});

	// Log a message to confirm the event listener is attached
	console.log("Event listener successfully attached to the form.");
  } else {
	console.error("Form with ID 'addPatientForm' not found.");
  }
});

function loadPatients() {
	let endpoint = null;
	let place = null;
	const url = window.location.href;

	if (window.location.href.includes("nurse")) {
		endpoint = "../../functions/nurse_patients.inc.php";
		place = "nurse";
	} else if (window.location.href.includes("admin")) {
		endpoint = '../../functions/patients.inc.php';
		place = "admin";
	}
	
	fetch(endpoint, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			type: 'getPatients'
		})
	})
	.then(response => {
		return response.json()
	})
	.then(data => {
		if (data.status === "success" && data.data) {
			console.log(data.data);
			updateTableWithData(data.data, place);
		} else {
			alert(data.message || "No patients data available");
		}
	})
}

function updateTableWithData(patients, place) {
  const tableBody = document.getElementById('patientTable').querySelector('tbody');
  tableBody.innerHTML = '';

  patients.forEach(patient => {
	const row = document.createElement('tr');

	row.setAttribute('data-id', patient["id"]);
	fullName = patient["first_name"] + ' ' + patient["last_name"]
	
	if (place === "admin") {
		row.innerHTML = `
			<td>${patient["id"]}</td>
			<td>${fullName}</td>
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
	} else if (place === "nurse") {
		row.innerHTML = row.innerHTML = `
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
	}
	tableBody.append(row);
  })
}

