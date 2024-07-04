// File verified

/**
 * Handle the logout process.
 * 
 * This function listens for a logout button click event and initiates the logout process.
 * 
 * @function handleLogout
 * @param {HTMLElement} button - The logout button element.
 * @param {string} redirectionPath - The path to redirect to after logout.
 */
export function handleLogout(button, redirectionPath) {
    // Add event listener to the logout button
    button.addEventListener('click', function (event) {
        // Prevent default form submission behavior
        event.preventDefault();
        // Ask for confirmation before logout
        var confirmation = confirm("Souhaitez-vous vous déconnecter?");

        // If user confirms logout
        if (confirmation) {
            // Redirect to the specified path after logout
            window.location.href = redirectionPath;
        }
    });
}

/**
 * Display the specified content when the button is clicked.
 * 
 * This function listens for a button click event and toggles the display of the specified content.
 * 
 * @function displayContent
 * @param {HTMLElement} button - The button element.
 * @param {HTMLElement} content - The content element to be displayed or hidden.
 */
export function displayContent(button, content) {
    // Add event listener to the button
    button.addEventListener('click', function (event) {
        // Prevent default behavior of the button click
        event.preventDefault();

        // Toggle the display of the content
        if (content.style.display !== 'flex') {
            // If content is not currently displayed, show it
            content.style.display = 'flex';
            content.style.flexDirection = 'column';
            // Update button text based on content display
            if (button.textContent === 'Organiser mon séjour') {
                button.textContent = 'Annuler mon séjour';
            } else if (button.textContent === 'Ajouter un nouveau docteur') {
                button.textContent = 'Annuler le nouvel ajout docteur';
            } else if (button.textContent === 'Modifier le planning des docteurs') {
                button.textContent = 'Annuler l\'affectation des plannings docteurs';
            }
        } else {
            // If content is currently displayed, hide it
            content.style.display = 'none';
            // Update button text based on content display
            if (button.textContent === 'Annuler mon séjour') {
                button.textContent = 'Organiser mon séjour';
            } else if (button.textContent === 'Annuler le nouvel ajout docteur') {
                button.textContent = 'Ajouter un nouveau docteur';
            } else if (button.textContent === 'Annuler l\'affectation des plannings docteurs') {
                button.textContent = 'Modifier le planning des docteurs';
            }
        }
    });
}

/**
 * Display a list based on specified dates.
 * 
 * This function enables or disables a list based on the input dates. It listens for changes in the entrance and release dates to determine the list's availability.
 * 
 * @param {HTMLElement} entranceDate - The input field representing the entrance date.
 * @param {HTMLElement} myList - The list to be enabled or disabled.
 * @param {HTMLElement} releaseDate - The optional input field representing the release date.
 */
export function displayList(entranceDate, myList, releaseDate = null) {
    /**
     * Check dates and enable/disable the list accordingly.
     */
    function checkDates() {
        if (entranceDate.value !== '' && (releaseDate === null || releaseDate.value !== '')) {
            myList.disabled = false;
        } else {
            myList.disabled = true;
        }
    }

    // Listen for changes in the entrance date
    entranceDate.addEventListener('input', function (event) {
        event.preventDefault();
        checkDates();
    });

    // Listen for changes in the release date if it's provided
    if (releaseDate !== null) {
        releaseDate.addEventListener('input', function (event) {
            event.preventDefault();
            checkDates();
        });
    }
}

/**
 * Activate the submit form button when all inputs and selects are filled (for "appointment").
 * 
 * This function activates the submit form button when all required inputs and selects are filled.
 * 
 * @function checkAppointmentForm
 * @param {HTMLElement} form - The form element containing the inputs and selects.
 * @param {HTMLElement} submit - The submit button for the form.
 */
export function checkAppointmentForm(form, submit) {
    // Add change event listener to the form
    form.addEventListener('change', function () {
        // Initialize flag for checking if all fields are filled
        let allFieldsFilled = true;
        // Reset submit button color
        submit.style.color = '';

        // Check all input fields
        form.querySelectorAll('input').forEach(function (input) {
            // If any input field is empty, set flag to false
            if (input.value.trim() === '') {
                allFieldsFilled = false;
            }
        });

        // Check all select fields
        form.querySelectorAll('select').forEach(function (select) {
            // If any select field is empty, set flag to false
            if (select.value.trim() === '') {
                allFieldsFilled = false;
            }
        });

        // Disable or enable submit button based on allFieldsFilled flag
        submit.disabled = !allFieldsFilled;

        // If all fields are filled, change submit button color to black
        if (allFieldsFilled) {
            submit.style.color = 'black';
        }
    });
}
