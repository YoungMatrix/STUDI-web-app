// File verified

/**
 * Import necessary functions and variables.
 */
import * as functions from '../../utilities/functions/js/publicFunction.js';

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
functions.displayRemoveContent(myButtonSignup, myButtonLogin, myContentSignup, myContentLogin);

// Close the sign-up content when the sign-up button is clicked
functions.closeContent(myButtonSignup, myContentSignup);

// Close the login content when the login button is clicked
functions.closeContent(myButtonLogin, myContentLogin);

// Display an error message in the sign-up section
functions.displayError(signupError, signupErrorContent, myContentSignup);

// Display an error message in the login section
functions.displayError(loginError, loginErrorContent, myContentLogin);
