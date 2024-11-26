const TABLE_COLUMNS = {
    patients: [
      { key: 'id', label: 'Patient ID' },
      { key: 'name', label: 'Patient Name' },
      { key: 'admission_date', label: 'Admission Date' }
    ],
    critical: [
      { key: 'id', label: 'Patient ID' },
      { key: 'name', label: 'Name' }
    ],

};

function generateTable(columns, data) {
    return `
    <div class="space-y-6">
      <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr>
              ${columns.map(col => 
                `<th class="p-3 text-left border-b">${col.label}</th>`
              ).join('')}
            </tr>
          </thead>
          <tbody>
            ${data.map(row => `
              <tr>
                ${columns.map(col => 
                  `<td class="p-3 border-b">${row[col.key] || ''}</td>`
                ).join('')}
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
    `;
}

$(document).ready(function () {
    const id = 3;
    const url = '../../functions/doctor/get_appointments.php?id=' + id ;
    const obj = $("#patientList");
    $.ajax({
        url: url, // Your PHP script
        type: 'GET', // PHP often handles DELETE requests as POST for simplicity
        dataType: 'json', // Expecting JSON response
    success: function (data) {
        console.log(data);
        obj.empty();
        data.forEach(function(patient){
            const row = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1">${patient.patient_name}</h6>
                        </div>
                        <div class="action-icons">
                            <a href="#" id="confirm" data-id="${patient.appointment_id}"><i class="bi bi-clipboard-check-fill fs-4"></i></a>
                            <a href="#"><i class="fas fa-edit fs-4 open-btn" data-id="${patient.id}"></i></a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small>${patient.age}/${patient.gender}</small>
                        <span class="status-badge ${patient.patient_status}">${patient.patient_status}</span>
                    </div>
                    <br>
                `;
            obj.append(row);
        });
    },
    error: function (xhr, status, error) {
        console.error('Error fetching data:', error);
    }
    });


    // URL for JSON data (replace with your actual endpoint)
    $('.see-details').click(function () {
        const title = $(this).data("title");
        const type = $(this).data("table");
        console.log(type)
        // const id = 2;
        const url = '../../functions/doctor/get_modal_data.inc.php?table=' + type + "&&id=" + id;
        // Perform actions on button click
        console.log('Button was clicked!');
        $.ajax({
            url: url, // Your PHP script
            type: 'GET', // PHP often handles DELETE requests as POST for simplicity
            dataType: 'json', // Expecting JSON response
        success: function (data) {
            console.log(data);
            $('#modal-title').text(title);
            $('#modal-content').html(generateTable(TABLE_COLUMNS[type], data));
            // Assuming `data` is an array of objects like [{id: 1, name: "John", email: "john@example.com"}]
            $('#modal')
            .removeClass('opacity-0 pointer-events-none')
            .addClass('opacity-100 pointer-events-auto');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching data:', error);
        }
        });

    });

    $('#close').click(function () {
        console.log("Button clicked");
        $('#modal')
            .removeClass('opacity-100 pointer-events-auto')
            .addClass('opacity-0 pointer-events-none');
    });


    // AJAX request to fetch data
});