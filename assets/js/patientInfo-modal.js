console.log("loaded");

const displayModal = document.getElementById('displayModal');
const modal = new bootstrap.Modal(displayModal);

// Add click event listener to each button
document.addEventListener('click', async function(e) { 
    if (e.target.matches('.open-btn')) { 
        e.preventDefault();
        
        // Get the patient ID from the clicked button's data attribute
        const patientId = e.target.getAttribute('data-id');
        console.log('Button clicked, Patient ID:', patientId);
        
        try {
            // Fetch the data first
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
            
            // Populate the modal with the fetched data
            populateModal(data.data);

            // Only after data is loaded and populated, show the modal
            modal.show();

        } catch (error) {
            console.error('There has been a problem with your fetch operation:', error);
            alert('Failed to load patient details. Please try again.');
        }
    };
});

function populateModal(data) {
    const fullName = data["first_name"] + ' ' + data["last_name"];

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