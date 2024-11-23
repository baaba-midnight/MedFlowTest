document.addEventListener('DOMContentLoaded', function() {
    let userId;

    // Modal handling
    const displayModal = document.getElementById('userDetails');

    // attach event listerner to the madal's 'shown.bs.modal' event
   displayModal.addEventListener('shown.bs.modal', function(event) {
        // get the button that triggered the modal
        const button = event.relatedTarget;
        userId = button.getAttribute('data-id');

        console.log(userId);

        if (userId) {
            // fetch user details
            getInfo(userId);
        }
   });

    // function to fetch and display user details
    async function getInfo() {
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
            console.log(data);

            // populate the modal with user detials
            console.log(userId);
            populateModal(data.data);
        } catch (error) {
            console.error('There has been a problem with your fetch operation:', error);
        }
    };

    // function to populate the modal with the fetched data
    function populateModal(data) {

        // load user info
        document.getElementById('userID').textContent = data['id'] || 'N/A';
        document.getElementById('userName').textContent = data['full_name'] || 'N/A';
        document.getElementById('userEmail').textContent = data['email'] || 'N/A';
        document.getElementById('role').textContent = data['role'] || 'N/A';
    }
});