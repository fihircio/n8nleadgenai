/*
* Spinner plugin - Author: tcja
* Version: 1.7
*/

// Function to create and insert the spinner HTML
export function createSpinner(options = {}) {
    const defaults = {
        spinnerId: "spinner",
        spinnerText: "Processing your request",
        longWaitText: "This is taking longer than expected. Please wait",
        insertTarget: "#showSpinner",
        spinnerColor: "blue-500",
        spinnerSize: "h-24 w-24",
        borderWidth: "border-8",
        textColor: "text-gray-700",
        textSize: "text-lg"
    };

    const config = { ...defaults, ...options };

    const spinner = document.createElement('div');
    spinner.id = config.spinnerId;
    spinner.classList.add('hidden', 'flex', 'flex-col', 'items-center', 'm-auto');
    spinner.style.marginTop = '3rem';
    spinner.style.marginBottom = '3rem';

    // Create style element and add the animation CSS
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes loading-dots {
            0% { opacity: 0; }
            25% { opacity: 1; }
            50% { opacity: 1; }
            75% { opacity: 0; }
            100% { opacity: 0; }
        }

        .dot-1 {
            animation: loading-dots 2s infinite;
        }
        .dot-2 {
            animation: loading-dots 2s infinite 0.33s;
        }
        .dot-3 {
            animation: loading-dots 2s infinite 0.66s;
        }

        @keyframes fade {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }

        .fade-text {
            animation: fade 0.7s ease-in-out;
        }
    `;
    spinner.appendChild(style);

    // Check if spinnerColor is a hexadecimal color
    const isHexColor = /^#[0-9A-Fa-f]{6}$/i.test(config.spinnerColor);

    // Add the spinner HTML content
    spinner.innerHTML += `
        <div class="animate-spin ${config.spinnerSize} ${config.borderWidth} ${isHexColor ? '' : `border-${config.spinnerColor}`} rounded-full border-t-transparent" ${isHexColor ? `style="border-color: ${config.spinnerColor}; border-top-color: transparent;"` : ''}></div>
        <p id="spinnerMessage" class="mt-4 ${config.textSize} font-medium ${config.textColor} text-center">${config.spinnerText}<span class="dot-1">.</span><span class="dot-2">.</span><span class="dot-3">.</span></p>
    `;

    // Find the target element for insertion
    let targetElement;
    if (config.insertTarget.startsWith('#')) { // ID
        targetElement = document.getElementById(config.insertTarget.slice(1));
    } else if (config.insertTarget.startsWith('.')) { // Class
        targetElement = document.querySelector(config.insertTarget);
    } else { // Element name
        targetElement = document.querySelector(config.insertTarget);
    }

    // Append the spinner to the target or fallback to body
    if (targetElement) {
        targetElement.appendChild(spinner);
    } else {
        console.error(`Target element '${config.insertTarget}' not found. Appending to body instead.`);
        document.body.appendChild(spinner);
    }

    // Start the text changing loop
    startTextChangeLoop(config);
}

// Function to start the text changing loop
function startTextChangeLoop(config) {
    const messageElement = document.getElementById('spinnerMessage');
    let isDefaultText = true;

    function changeText() {
        messageElement.classList.add('fade-text');
        setTimeout(() => {
            if (isDefaultText) {
                messageElement.innerHTML = `${config.longWaitText}<span class="dot-1">.</span><span class="dot-2">.</span><span class="dot-3">.</span>`;
            } else {
                messageElement.innerHTML = `${config.spinnerText}<span class="dot-1">.</span><span class="dot-2">.</span><span class="dot-3">.</span>`;
            }
            isDefaultText = !isDefaultText;
            messageElement.classList.remove('fade-text');
        }, 350);
    }

    setInterval(() => {
        changeText();
    }, isDefaultText ? 13000 : 5000);
}

// Main function to hide elements and show the spinner
export function hideElementsAndShowSpinner(options = {}) {
    const defaults = {
        idsToHide: [],
        spinnerId: "spinner",
        timeout: 500
    };

    const config = { ...defaults, ...options };

    setTimeout(() => {
        config.idsToHide.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('hidden');
            }
        });

        const spinner = document.getElementById(config.spinnerId);
        if (spinner) {
            spinner.classList.remove('hidden');
        } else {
            console.error(`Spinner with ID '${config.spinnerId}' not found.`);
        }
    }, config.timeout);
}
