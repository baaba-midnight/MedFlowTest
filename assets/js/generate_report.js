$(document).ready(function() {
    $(document).on('click', '.generate-report', function(e) {
        e.preventDefault();
        
        const targetButton = $(this);
        
        // Check multiple criteria
        if (!targetButton.data('id')) {
            console.error('No patient ID found');
            return;
        }
        
        const patientId = targetButton.data('id');
            
        window.location.href = `../../functions/report_generator.php?patient_id=${patientId}`;
    });
});