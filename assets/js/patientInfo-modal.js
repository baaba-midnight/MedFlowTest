document.addEventListener('DOMContentLoaded', function() {
    let patientId;

    // Modal handling
    const displayModal = document.getElementById('displayModal');

    // attach event listerner to the madal's 'shown.bs.modal' event
   displayModal.addEventListener('shown.bs.modal', function(event) {
        // get the button that triggered the modal
        const button = event.relatedTarget;
        patientId = button.getAttribute('data-id');

        console.log(patientId + "This is it");

        if (patientId) {
            // fetch patient details
            getInfo(patientId);
        }
   });

    // function to fetch and display patient details
    async function getInfo() {
        try {
            const response = await fetch(`../../functions/patients.inc.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'getPatientById',
                    id: patientId
                })
            });
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const data = await response.json();
            console.log(data);

            // populate the modal with patient detials
            console.log(patientId);
            populateModal(data.data);
        } catch (error) {
            console.error('There has been a problem with your fetch operation:', error);
        }
    };

    // function to populate the modal with the fetched data
    function populateModal(data) {
        console.log(data);
        fullName = data["first_name"] + ' ' + data["last_name"];

        // load patient basic info
        document.getElementById('patientID').textContent = data['id'] || 'N/A';
        document.getElementById('patientName').textContent = fullName || 'N/A';
        document.getElementById('patientAge').textContent = data['age'] || 'N/A';
        document.getElementById('patientGender').textContent = data['gender'] || 'N/A'

        let element = document.getElementById('statusInfo');

        // Get the class list
        let classList = element.classList;

        // Remove all classes except the first one
        while (classList.length > 1) {
            classList.remove(classList[1]);
        }
        element.textContent = data['status'] || 'N/A';
        element.classList.add(data['status']);

        document.getElementById('statusInfo').classList.add(`${data['status']}`);

        document.getElementById('patientAdmissionDate').textContent = data['admission_date'] || 'N/A';

        // load patient contact information
        document.getElementById('patientContact').textContent = data['phone'] || 'N/A';
        document.getElementById('patientAddress').textContent = data['address'] || 'N/A';

        // load patient notes
        document.getElementById('patientNotes').value = data['notes'] || 'N/A';
    }
});