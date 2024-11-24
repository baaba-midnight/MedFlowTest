document.addEventListener('DOMContentLoaded', function() {
    // Fetch doctors for dropdown
    fetch('../../functions/get_doctors.php')
        .then(response => response.json())
        .then(doctors => {
            const dropdown = document.getElementById('doctorAssign');
            doctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `Dr. ${doctor.fullName}`;
                dropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching doctors:', error));

    // Get modal element
    const appointmentModal = document.getElementById('addAppointmentModal');
    let patientId; // Declare patientId in the outer scope

    // Add modal show event listener
    appointmentModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Button that triggered the modal
        patientId = button.getAttribute('data-id'); // Get patient ID from button
        console.log("Patient ID from modal open: ", patientId);
    });

    // Handle Form Submission
    const formElement = document.getElementById('addAppointmentForm');
    console.log(formElement ? 'yes' : 'no');
    
    if (formElement) {
        formElement.addEventListener('submit', async function submitAppointment(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const appointmentData = Object.fromEntries(formData);

            console.log(appointmentData);

            // validate input fields
            if (!appointmentData.doctorAssign) {
                alert("Please select a doctor");
                return;
            }

            console.log(JSON.stringify({
                patientId, // Use the patientId from modal event
                ...appointmentData
            }));

            try {
                const response = await fetch("../../functions/nurse/add_appointment.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        patientId, // Use the patientId from modal event
                        ...appointmentData
                    })
                });

                const result = await response.json();

                if(result.status === "success") {
                    alert(result.message);
                    e.target.reset();
                    // reload the table
                    loadPatients();

                    // Close the modal after successful submission
                    const modal = bootstrap.Modal.getInstance(appointmentModal);
                    modal.hide();
                } else {
                    alert(result.message || "Error adding new appointment");
                }
            } catch (error) {
                console.error('Error:', error);
                alert("Error submitting form");
            }
        });
    } else {
        console.error("Form with ID 'addAppointmentForm' not found.");
    }
});