document.addEventListener('DOMContentLoaded', () => {
	const formElement = document.getElementById('addUserForm');

	if (formElement) {
		formElement.addEventListener('submit', async function submitUserForm(event) {
			event.preventDefault();

			// Log a message to confirm the event is triggered
			console.log("Submit event triggered");

			const formData = new FormData(event.target);
			const userData = Object.fromEntries(formData);
			console.log(userData);

			// Validate input fields
			if (!userData.fname || !userData.lname || !userData.password) {
				alert("Please fill all required fields");
				return;
			}
			

			console.log(userData.password);
        	console.log(userData.confirmPassword);

			if (userData.password !== userData.confirmPassword) {
				alert("Passwords do not match");
				return;
			}

			const type = 'addUser';
			console.log(userData.fname);

			console.log(JSON.stringify({ type, ...userData }));

			try {
				const response = await fetch('../../functions/users.inc.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({ type, ...userData }),
				});

				const result = await response.json();
				console.log(result);
				if (result.data.message === 'User added successfully') {
					// refresh the table content
					loadUsers();

					alert(result.data.message);
					event.target.reset();
				} else {
					alert("Error adding user");
				}
			} catch (error) {
				console.error('Error:', error);
			}
		});

		// Log a message to confirm the event listener is attached
		console.log("Event listener successfully attached to the form.");
	} else {
		console.error("Form with ID 'addUserForm' not found.");
	}
});

function loadUsers() {
	fetch('../../functions/users.inc.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			type: 'getUsers'
		})
	})
		.then(response => {
			return response.json()
		})
		.then(data => {
			if (data.status === "success" && data.data) {
				console.log(data.data);
				updateTableWithData(data.data);
			} else {
				alert(data.message || "No users data available");
			}
		})
}

function updateTableWithData(users) {
	const tableBody = document.getElementById('userTable').querySelector('tbody');
	tableBody.innerHTML = '';

	users.forEach(user => {
		const row = document.createElement('tr');

		row.setAttribute('data-id', user["id"]);
		fullName = user["first_name"] + ' ' + user["last_name"]

		row.innerHTML = `
                <td>${user["id"]}</td>
                <td>${fullName}</td>
                <td>${user['email']}</td>
                <td>${user["role"]}</td>
                <td>
                    <div class="selected-actions" id="selectedActions">
                        <button type="button" class="action-btn edit-btn" data-id="${user["id"]}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                            <span class="action-icon">✏️</span> Edit
                        </button>
                        <button class="action-btn remove-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-id="${user["id"]}">
                            <span class="action-icon">🗑️</span> Remove
                        </button>
                        <button class="action-btn open-btn" data-bs-toggle="modal" data-bs-target="#userDetails" data-id="${user["id"]}">
                            <span class="action-icon">📂</span> Open
                        </button>
                    </div>
                </td>
            `;
		tableBody.append(row);
	})
}