document.addEventListener('DOMContentLoaded',  () => {
    const editModal = document.getElementById("myModal");
    let patientId;

    editModal.addEventListener('shown.bs.modal', function(e) {
        const button = e.relatedTarget;
        patientId = button.getAttribute('data-id');
        console.log("Patient ID from modal open: ", patientId);
    });

    console.log(patientId);

    const formElement = document.getElementById('editPatientForm');
    console.log(formElement ? 'yes' : 'no');

    if (formElement) {
        formElement.addEventListener('submit', async function submitPatientForm(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const patientData = Object.fromEntries(formData);

            const type = "updatePatient";

            console.log(JSON.stringify({type, patientId, ...patientData}));

            try {
                const response = await fetch('../../functions/update.inc.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({type, patientId, ...patientData})
                });

                const result = await response.json();
                console.log(result);
                
                if (result.status === 'success') {
                    loadPatients();
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