// File verified

/**
 * Import necessary functions.
 */
import * as commonFunction from '../../utilities/functions/js/commonFunction.js';
import * as userFunction from '../../utilities/functions/js/userFunction.js';

/**
 * Path to the public controller file.
 * @constant {string} publicControllerPath - The path to the public controller PHP file.
 */
const publicControllerPath = '../../controller/public/publicController.php';

/**
 * DOM elements for historical data.
 * 
 * @constant {HTMLElement} myButtonHistorical - The button element for accessing historical data.
 * @constant {HTMLElement} myHistoricalList - The list element for displaying historical records.
 */
const myButtonHistorical = document.getElementById('historicalJS');
const myHistoricalList = document.getElementById('historicalListJS');

/**
 * DOM elements for logout functionality.
 * 
 * @constant {HTMLElement} myButtonLogout - The button element for logging out.
 */
const myButtonLogout = document.getElementById('logoutJS');

/**
 * DOM elements and variables for appointment scheduling.
 * 
 * @constant {HTMLElement} entranceDate - The input element for selecting entrance date.
 * @constant {HTMLElement} releaseDate - The input element for selecting release date.
 * @constant {HTMLElement} mySelectPattern - The select element for selecting a pattern.
 * @constant {HTMLElement} mySelectField - The select element for selecting a field.
 * @constant {HTMLElement} myDoctorList - The list element for displaying available doctors.
 */
const entranceDate = document.getElementById('entranceDateJS');
const releaseDate = document.getElementById('releaseDateJS');
const mySelectPattern = document.getElementById('patternJS');
const mySelectField = document.getElementById('fieldJS');
const myDoctorList = document.getElementById('doctorJS');

/**
 * DOM elements and functionality for appointment booking.
 * 
 * @constant {HTMLElement} myButtonAppointment - The button element for initiating an appointment.
 * @constant {HTMLElement} myAppointmentForm - The form element for booking appointments.
 * @constant {HTMLElement} myAppointmentSubmit - The submit button for the appointment form.
 */
const myButtonAppointment = document.getElementById('appointmentJS');
const myAppointmentForm = document.getElementById('appointmentBoxJS');
const myAppointmentSubmit = document.getElementById('submitAppointmentJS');

// Display historical data when the button is clicked
commonFunction.displayContent(myButtonHistorical, myHistoricalList);

// Display appointment form when the button is clicked
commonFunction.displayContent(myButtonAppointment, myAppointmentForm);

// Handle logout functionality
commonFunction.handleLogout(myButtonLogout, publicControllerPath);

// Display options in pattern select element based on entrance and release dates
commonFunction.displayList(entranceDate, mySelectPattern, releaseDate);

// Display options in field select element based on entrance and release dates
commonFunction.displayList(entranceDate, mySelectField, releaseDate);

// Check the appointment form before submitting
commonFunction.checkAppointmentForm(myAppointmentForm, myAppointmentSubmit);

// Display list of doctors based on selected field
userFunction.displayDoctorList(mySelectField, myDoctorList, doctorMap);

// Show alert messages for appointment success or error
userFunction.appointmentAlert(appointmentSuccess, appointmentError);
