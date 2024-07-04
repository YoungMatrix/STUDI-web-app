// File verified

/**
 * Activate the submit form button when all inputs are correctly filled.
 * 
 * This function activates the submit form button when all required inputs are correctly filled.
 * It also provides validation logic specific to different types of forms.
 * 
 * @function activateFormButton
 * @param {HTMLElement} submitButton - The submit button for the form.
 * @param {HTMLElement[]} inputs - Array of input elements.
 */
export function activateFormButton(submitButton, inputs) {
    document.addEventListener('input', function (event) {
        event.preventDefault();
        let allInputsFilled = true;

        // Check conditions for adding a new doctor form
        if (!areAllCharactersAlpha(inputs[0].value.trim()) || !areAllCharactersAlpha(inputs[1].value.trim()) || inputs[2].value === '') {
            allInputsFilled = false;
        }

        // Disable or enable submit button based on input validation
        if (!allInputsFilled) {
            submitButton.disabled = true;
            submitButton.style.color = '';
        } else {
            submitButton.disabled = false;
            submitButton.style.color = 'black';
        }
    });
}

/**
 * Verify if all characters of a word are alphabetic.
 * 
 * @function areAllCharactersAlpha
 * @param {string} word - The word to be verified.
 * @returns {boolean} True if all characters are alphabetic, otherwise false.
 */
function areAllCharactersAlpha(word) {
    return /^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/u.test(word.replace(/\s/g, ''));
}

/**
 * Display alerts for doctor-related actions.
 * 
 * This function displays alerts for different doctor-related actions such as adding a new doctor or changing planning.
 * 
 * @function doctorAlert
 * @param {boolean} newDoctorSuccess - Indicates whether adding a new doctor was successful.
 * @param {boolean} newDoctorError - Indicates whether adding a new doctor resulted in an error.
 * @param {boolean} changePlanningSuccess - Indicates whether changing planning was successful.
 * @param {boolean} changePlanningError - Indicates whether changing planning resulted in an error.
 */
export function doctorAlert(newDoctorSuccess, newDoctorError, changePlanningSuccess, changePlanningError) {
    if (newDoctorSuccess) {
        alert('Docteur enregistré avec succès.');
    } else {
        if (newDoctorError) {
            alert('Docteur non enregistré.\
            \nLe serveur n\'est pas disponible.\
            \nVeuillez recommencer ultérieurement.');
        }
    }

    if (changePlanningSuccess) {
        alert('Planning enregistré avec succès.');
    } else {
        if (changePlanningError) {
            alert('Planning non enregistré.\
            \nLimite atteinte de 5 patients par docteur ou docteur similaire ou spécialité différente.\
            \nVeuillez recommencer.');
        }
    }
}