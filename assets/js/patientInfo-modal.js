document.addEventListener('DOMContentLoaded', function() {
    let patientId;
    // Tab handling
    const medicalLinks = document.querySelectorAll('.nav-link.medical');
    
    medicalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and tab panes
            medicalLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active', 'show');
            });
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Get the target tab from href and activate it
            const tabId = this.getAttribute('href');
            const targetTab = document.querySelector(tabId);
            if (targetTab) {
                targetTab.classList.add('active', 'show');
            }
        });
    });

    // Modal handling
    const displayModal = document.getElementById('displayModal');

    // attach event listerner to the madal's 'shown.bs.modal' event
   displayModal.addEventListener('shown.bs.modal', function(event) {
        // get the button that triggered the modal
        const button = event.relatedTarget;
        patientId = button.getAttribute('data-id');

        console.log(patientId);

        if (patientId) {
            // fetch patient details
            getInfo(patientId);
        }
   });

    // function to fetch and display patient details
    async function getInfo() {
        try {
            const response = await fetch(`../../functions/manage_patient/display_info.inc.php?id=${patientId}`);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            const data = await response.json();
            console.log(data);

            // populate the modal with patient detials
            console.log(patientId);
            populateModal(data);
        } catch (error) {
            console.error('There has been a problem with your fetch operation:', error);
        }
    };

    function clearTable(dataTable) {
        dataTable.innerHTML = ''; // Clears all rows in the <tbody>
    }

    // function to populate the modal with the fetched data
    function populateModal(data) {
        console.log(data);
        // load patient vitals
        document.getElementById('blood_pressure').textContent = data.Vitals['blood_pressure'] + ' ' + 'mmHg';
        document.getElementById('heart_rate').textContent = data.Vitals['heart_rate'] + ' ' + 'bpm';
        document.getElementById('temperature').textContent = data.Vitals['temperature'] + ' ' + '°C';

        // load patient basic info
        document.getElementById('patientID').textContent = data.BasicInfo['patient_id'] || 'N/A';
        document.getElementById('patientName').textContent = data.BasicInfo['full_name'] || 'N/A';
        document.getElementById('patientAge').textContent = data.BasicInfo['age'] || 'N/A';
        document.getElementById('patientGender').textContent = data.BasicInfo['gender'] || 'N/A';
        document.getElementById('patientDepartment').textContent = data.BasicInfo['departments'] || 'N/A';
        document.getElementById('statusInfo').textContent = data.BasicInfo['status'] || 'N/A';
        document.getElementById('statusInfo').classList.add(`${data.BasicInfo['status']}`);

        document.getElementById('patientAdmissionDate').textContent = data.BasicInfo['admission_date'] || 'N/A';

        // load patient contact information
        document.getElementById('patientContact').textContent = data.BasicInfo['contact_number'] || 'N/A';
        document.getElementById('patientEmail').textContent = data.BasicInfo['email'] || 'N/A';
        document.getElementById('patientAddress').textContent = data.BasicInfo['address'] || 'N/A';
    }
});