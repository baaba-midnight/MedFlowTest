document.addEventListener('DOMContentLoaded',  () => {
    const formElement = document.getElementById("myModal");
    let userId;

    formElement.addEventListener('show.bs.modal', function(e) {
        const button = e.relatedTarget;
        userId = button.getAttribute('data-id');
        console.log("User ID from modal open: ", userId);
    });

    if (formElement) {
        formElement.addEventListener('submit', async function submitUserForm(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const patientData = Object.fromEntries(formData);

            const type = "updateUser";

            try {
                const response = await fetch('../../functions/update.inc.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({type, userId, ...patientData})
                });

                const result = await response.json();
                
                if (result.status === 'success') {
                    loadUsers();
                    alert(result.data.message);
                    e.target.reset();
                } else {
                    alert("Error updating patient details");
                }
            } catch (error) {
                console.log('Error:', error);
            }
        })
    } else {
        console.error("Form with ID 'myModal' not found.");
    }
})