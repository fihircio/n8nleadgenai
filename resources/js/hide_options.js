(function() {
    let currentPath = '';

    function initializeScript() {
        if (/^\/admin\/navigations\/.*/.test(window.location.pathname)) {

            function hideOptionsInSelect(selectElement) {
                const optionsToRemove = Array.from(selectElement.options).filter(option =>
                    option.value === 'library_link' || option.value === 'post_link'
                );
                optionsToRemove.forEach(option => {
                    option.remove();
                });
                return optionsToRemove.length > 0;
            }

            function hideOptionsInModal() {
                const selectElements = document.querySelectorAll('.fi-modal-content select');
                if (selectElements.length === 0) return; // Exit if no select elements are found

                selectElements.forEach(selectElement => {
                    if (!selectElement.dataset.eventAttached) {
                        selectElement.addEventListener('change', function() {
                            hideOptionsInSelect(selectElement);
                        });
                        selectElement.addEventListener('focus', function() {
                            hideOptionsInSelect(selectElement);
                        });
                        selectElement.dataset.eventAttached = 'true';
                    }
                    hideOptionsInSelect(selectElement);
                });
            }

            function hideOptionsInSelect(selectElement) {
                const optionsToRemove = Array.from(selectElement.options).filter(option =>
                    option.value === 'library_link' || option.value === 'post_link'
                );
                optionsToRemove.forEach(option => {
                    option.remove();
                });
            }

            // Initial hide attempt
            hideOptionsInModal();

            function handleMutations(mutations) {
                let shouldHideOptions = false;

                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === Node.ELEMENT_NODE && (node.tagName === 'SELECT' || node.querySelector('select'))) {
                                shouldHideOptions = true;
                            }
                        });
                    }
                });

                if (shouldHideOptions) {
                    hideOptionsInModal();
                }
            }

            const observer = new MutationObserver(handleMutations);

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Initial hide attempt
            hideOptionsInModal();
        }
    }

    function checkForRouteChange() {
        if (currentPath !== window.location.pathname) {
            currentPath = window.location.pathname;
            initializeScript();
        }
    }

    // Initial run
    initializeScript();

    // Listen for popstate events (back/forward navigation)
    window.addEventListener('popstate', initializeScript);

    // Periodically check for route changes (for other navigation methods)
    setInterval(checkForRouteChange, 500);
})();
