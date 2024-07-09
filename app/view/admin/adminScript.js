// File verified

/**
 * Import necessary functions.
 */
import * as commonFunction from '../../utilities/functions/js/commonFunction.js';
import * as adminFunction from '../../utilities/functions/js/adminFunction.js';

/**
 * Path to the public controller file.
 * @constant {string} publicControllerPath - The path to the public controller PHP file.
 */
const publicControllerPath = '../../controller/public/PublicController.php';

/**
 * Elements related to planning.
 * @constant {HTMLElement} myButtonPlanning - The button element for planning.
 * @constant {HTMLElement} myPlanningList - The list element for planning.
 */
const myButtonPlanning = document.getElementById('planningJS');
const myPlanningList = document.getElementById('planningListJS');

/**
 * Element related to doctor list.
 * @constant {HTMLElement} myDoctorList - The element for the doctor list.
 */
const myDoctorList = document.getElementById('doctorListJS');

/**
 * Element related to logout button.
 * @constant {HTMLElement} myButtonLogout - The button element for logout.
 */
const myButtonLogout = document.getElementById('logoutJS');

/**
 * Elements related to doctor form.
 * @constant {HTMLElement} myField - The input field element for doctors.
 * @constant {HTMLElement} myButtonDoctor - The button element for doctors.
 */
const myField = document.getElementById('fieldJS');
const myButtonDoctor = document.getElementById('doctorJS');

/**
 * Elements related to adding a new doctor.
 * @constant {HTMLElement} myButtonNewDoctorJS - The button element for adding a new doctor.
 * @constant {HTMLElement} myNewDoctorForm - The form element for adding a new doctor.
 * @constant {HTMLElement} myNewDoctorLastName - The input element for new doctor's last name.
 * @constant {HTMLElement} myNewDoctorFirstName - The input element for new doctor's first name.
 * @constant {HTMLElement} myNewDoctorSubmit - The submit button element for adding a new doctor.
 */
const myButtonNewDoctorJS = document.getElementById('newDoctorJS');
const myNewDoctorForm = document.getElementById('newDoctorBoxJS');
const myNewDoctorLastName = document.getElementById('newDoctorLastNameJS');
const myNewDoctorFirstName = document.getElementById('newDoctorFirstNameJS');
const myNewDoctorSubmit = document.getElementById('submitNewDoctorJS');

/**
 * Elements related to doctor planning.
 * @constant {HTMLElement} myButtonDoctorPlanningJS - The button element for doctor planning.
 * @constant {HTMLElement} myDoctorPlanningForm - The form element for doctor planning.
 * @constant {HTMLElement} myPlanningIdJS - The element for planning ID.
 * @constant {HTMLElement} myOtherDoctorIdJS - The element for other doctor ID.
 * @constant {HTMLElement} myDoctorPlanningSubmit - The submit button element for doctor planning.
 */
const myButtonDoctorPlanningJS = document.getElementById('doctorPlanningJS');
const myDoctorPlanningForm = document.getElementById('doctorPlanningBoxJS');
const myPlanningIdJS = document.getElementById('planningIdJS');
const myOtherDoctorIdJS = document.getElementById('otherDoctorIdJS');
const myDoctorPlanningSubmit = document.getElementById('submitDoctorPlanningJS');

// Displays the appropriate content based on which button is clicked (planning or doctor).
commonFunction.displayRemoveContent(myButtonPlanning, myButtonDoctor, myPlanningList, myDoctorList);

// Handles the logout functionality by redirecting to the public controller path.
commonFunction.handleLogout(myButtonLogout, publicControllerPath);

// Displays the content for adding a new doctor when the corresponding button is clicked.
commonFunction.displayContent(myButtonNewDoctorJS, myNewDoctorForm);

// Checks if both the button for doctor planning and the doctor planning form exist.
if (myButtonDoctorPlanningJS && myDoctorPlanningForm) {
    // Displays the doctor planning form when the corresponding button is clicked.
    commonFunction.displayContent(myButtonDoctorPlanningJS, myDoctorPlanningForm);
}

// Activates the submit button for the new doctor form when the required fields are filled.
adminFunction.activateFormButton(myNewDoctorSubmit, [myNewDoctorLastName, myNewDoctorFirstName, myField]);

// Checks if both the planning ID and the other doctor ID elements exist.
if (myPlanningIdJS && myOtherDoctorIdJS) {
    // Displays the list of plans or doctors based on the given IDs.
    commonFunction.displayList(myPlanningIdJS, myOtherDoctorIdJS);
}

// Checks if both the doctor planning form and the submit button exist.
if (myDoctorPlanningForm && myDoctorPlanningSubmit) {
    // Validates the doctor planning form before submission.
    commonFunction.checkAppointmentForm(myDoctorPlanningForm, myDoctorPlanningSubmit);
}

// Displays alerts for different actions related to doctors and planning.
adminFunction.doctorAlert(newDoctorSuccess, newDoctorError, changePlanningSuccess, changePlanningError);
