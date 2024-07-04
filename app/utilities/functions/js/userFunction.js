// File verified

/**
 * Display the list based on the selected field.
 * 
 * This function listens for changes in the selected field and updates the doctor list accordingly.
 * 
 * @function displayDoctorList
 * @param {HTMLElement} mySelectedField - The selected field element.
 * @param {HTMLElement} myDoctorList - The doctor list element.
 * @param {Object} doctorMap - The mapping of fields to doctors.
 */
export function displayDoctorList(mySelectedField, myDoctorList, doctorMap) {
    // Listen for change event on the selected field
    mySelectedField.addEventListener('change', function () {
        // Get the value of the selected field
        var validatedfield = this.value;

        // Reset the doctor list and add a default option
        myDoctorList.innerHTML = '';
        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Choisissez un médecin';
        defaultOption.disabled = true;
        myDoctorList.appendChild(defaultOption);

        // Determine if the validated field key exists in doctorMap
        var found = false;
        Object.entries(doctorMap).forEach(([key, value]) => {
            if (key === validatedfield) {
                // If a match is found, add options and enable the list
                found = true;
                myDoctorList.disabled = false;
                for (var i = 0; i < value.length; i++) {
                    var option = document.createElement('option');
                    option.value = value[i];
                    option.text = 'Dr. ' + value[i];
                    myDoctorList.appendChild(option);
                }
            }
        });

        // If no match is found, disable the list and reset its value
        if (!found) {
            myDoctorList.disabled = true;
            myDoctorList.value = '';
        }
    });
}

/**
 * Display alerts for appointment-related actions.
 * 
 * This function displays alerts for different appointment-related actions such as booking an appointment.
 * 
 * @function appointmentAlert
 * @param {boolean} success - Indicates whether the appointment was successfully booked.
 * @param {boolean} error - Indicates whether an error occurred while booking the appointment.
 */
export function appointmentAlert(success, error) {
    if (success) {
        alert('Séjour enregistré avec succès.');
    } else {
        if (error) {
            alert('Séjour non enregistré.\
            \nLa date d\'entrée doit être antérieure à la date de sortie et postérieure à la date d\'aujourd\'hui.\
            \nDe plus, le séjour ne doit pas en chevaucher un autre.\
            \nVeuillez recommencer.');
        }
    }
}