// generate report functionality
$(document).ready(function() {
    // attach click event to buttons with the 'generate-report' class
    $('.generate-report').on('click', function() {
        // get the patient ID from the clicked button's data attribute
        const patientId = $(this).data('id');

        console.log("Received ID Report: ", $(this).data('id'));

        // redirect to the PHP file with the patient ID as a query parameter
        window.location.href = `../../functions/report_generator?patient_id=${patientId}`;
    });
});