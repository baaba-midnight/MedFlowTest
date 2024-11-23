async function submitPatientForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const patientData = Object.fromEntries(formData);

    if (!patientData.fname || !patientData.lname || !patientData.dob) {
      alert("Please fill all required fields");
      return;
    }
    const type = 'addPatient';
    
    try {
      const response = await fetch('../../functions/patient.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({type, ...patientData})
      });
      
      const result = await response.json();
      if (result.status === 'error') {
        alert("Error adding patient");
      } else {
        alert("Patient added successfully!");
        event.target.reset();
      }
      
    } catch (error) {
      // Handle error
      console.error('Error:', error);
    }
}
  
// Add event listener to your form
document.getElementById('addPatientForm').addEventListener('submit', submitPatientForm);