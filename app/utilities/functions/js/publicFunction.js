// File verified

/**
 * Display or remove the content elements when a button is clicked.
 * 
 * This function allows displaying or removing content elements based on user actions, such as clicking a button.
 * It handles the display of one content while hiding the other.
 * 
 * @param {HTMLElement} contentButton - The button that triggers the display of the content.
 * @param {HTMLElement} otherContentButton - The button associated with the other content.
 * @param {HTMLElement} content - The content to be displayed or removed.
 * @param {HTMLElement} otherContent - The content to be hidden when displaying the specified content.
 */
export function displayRemoveContent(contentButton, otherContentButton, content, otherContent) {
    // Add an event listener to the contentButton
    contentButton.addEventListener('click', function () {
        // If the content is not already displayed
        if (content.style.display !== 'flex') {
            // Display the content
            content.style.display = 'flex';
            // Set the flex direction to column
            content.style.flexDirection = 'column';
            // Hide the otherContent
            otherContent.style.display = 'none';
        } else {
            // Otherwise, hide the content
            content.style.display = 'none';
        }
    });

    // Add an event listener to the otherContentButton
    otherContentButton.addEventListener('click', function () {
        // If the otherContent is not already displayed
        if (otherContent.style.display !== 'flex') {
            // Display the otherContent
            otherContent.style.display = 'flex';
            // Set the flex direction to column
            otherContent.style.flexDirection = 'column';
            // Hide the content
            content.style.display = 'none';
        } else {
            // Otherwise, hide the otherContent
            otherContent.style.display = 'none';
        }
    });
}

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
        errorContent.style.display = 'flex';
        errorContent.style.flexDirection = 'column';
        formContent.style.display = 'flex';
        formContent.style.flexDirection = 'column';
        error = false;
    } else {
        errorContent.style.display = 'none';
        formContent.style.display = 'none';
    }
}
