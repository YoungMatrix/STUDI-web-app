// File verified

/**
 * Close the form when it loses focus.
 * 
 * This function closes the form when it loses focus, ensuring a clean user experience.
 * 
 * @param {HTMLElement} button - The button associated with the form.
 * @param {HTMLElement} content - The form content.
 */
export function closeContent(button, content) {
    document.addEventListener('click', function (event) {
        // Close the content if the click is not on the button or inside the content
        if (event.target !== button && !content.contains(event.target)) {
            content.style.display = "none";
        }
    });
}

/**
 * Display or hide error content and form content based on the error flag.
 * 
 * @param {boolean} error - Indicates whether there is an error.
 * @param {HTMLElement} errorContent - The DOM element representing the error content.
 * @param {HTMLElement} formContent - The DOM element representing the form content.
 */
export function displayError(error, errorContent, formContent) {
    if (error) {
        // Display error content and form content
        errorContent.style.display = 'flex';
        errorContent.style.flexDirection = 'column';
        formContent.style.display = 'flex';
        formContent.style.flexDirection = 'column';
        error = false; // Reset error flag
    } else {
        // Hide error content and form content
        errorContent.style.display = 'none';
        formContent.style.display = 'none';
    }
}
