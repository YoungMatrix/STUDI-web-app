// File verified

/**
 * Import necessary functions.
 */
import * as commonFunction from '../../utilities/functions/js/commonFunction.js';
import * as publicFunction from '../../utilities/functions/js/publicFunction.js';

/**
 * DOM elements for the "sign up" form.
 * 
 * @constant {HTMLElement} myButtonSignup - The button element for initiating the sign-up process.
 * @constant {HTMLElement} myContentSignup - The container element for the sign-up box.
 * @constant {HTMLElement} signupErrorContent - The element for displaying signup errors.
 */
const myButtonSignup = document.getElementById('signupJS');
const myContentSignup = document.getElementById('signupBoxJS');
const signupErrorContent = document.getElementById('signupErrorJS');

/**
 * DOM elements for the "log in" form.
 * 
 * @constant {HTMLElement} myButtonLogin - The anchor element for triggering the login action.
 * @constant {HTMLElement} myContentLogin - The container element for the login form section.
 * @constant {HTMLElement} loginErrorContent - The element for displaying login error messages.
 */
const myButtonLogin = document.getElementById('loginJS');
const myContentLogin = document.getElementById('loginBoxJS');
const loginErrorContent = document.getElementById('loginErrorJS');

// Display and remove content for the sign-up and login buttons
commonFunction.displayRemoveContent(myButtonSignup, myButtonLogin, myContentSignup, myContentLogin);

// Close the sign-up content when the sign-up button is clicked
publicFunction.closeContent(myButtonSignup, myContentSignup);

// Close the login content when the login button is clicked
publicFunction.closeContent(myButtonLogin, myContentLogin);

// Display an error message in the sign-up section
publicFunction.displayError(signupError, signupErrorContent, myContentSignup);

// Display an error message in the login section
publicFunction.displayError(loginError, loginErrorContent, myContentLogin);
