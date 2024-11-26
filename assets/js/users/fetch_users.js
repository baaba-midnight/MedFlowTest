// Fetch the JSON data from the PHP script
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
    // Check if data is an array (contains users) or if it's an error message
    if (data.status === "success" && data.data) {
        // Populate the table with the user data
        const tableBody = document.getElementById('userTable').querySelector('tbody');
        // const modals = document.getElementById('modals')
        data.data.forEach(user => {
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
                        <button type="button" class="action-btn edit-btn" data-id="${user["id"]}">
                            <span class="action-icon">✏️</span> Edit
                        </button>
                        <button class="action-btn remove-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-id="${user["id"]}">
                            <span class="action-icon">🗑️</span> Remove
                        </button>
                        <button class="action-btn open-btn" data-id="${user["id"]}">
                            <span class="action-icon">📂</span> Open
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);

        });
    } else {
        // Handle case where no users are available
        alert(data.message || "No users data available.");
    }
})
.catch(error => console.error('Error fetching user data:', error));