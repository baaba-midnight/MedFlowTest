const displayModal = document.getElementById('userDetails');
const modal = new bootstrap.Modal(displayModal);

let userId;

// attach event listerner to each button
document.addEventListener('click', async function(event) {
    if (event.target.matches('.open-btn')) {
        event.preventDefault();

        const userId = event.target.getAttribute('data-id');

        try {
            const response = await fetch(`../../functions/users.inc.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'getUserById',
                    id: userId
                })
            });
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const data = await response.json();

            populateModal(data.data);

            modal.show();
        } catch (error) {
            console.error('There has been a problem with your fetch operation:', error);
            alert('Failed to load user details. Please try again.');
        }
    } 
});

// function to populate the modal with the fetched data
function populateModal(data) {

    // load user info
    document.getElementById('userID').textContent = data['id'] || 'N/A';
    document.getElementById('userName').textContent = data['full_name'] || 'N/A';
    document.getElementById('userEmail').textContent = data['email'] || 'N/A';
    document.getElementById('role').textContent = data['role'] || 'N/A';
}