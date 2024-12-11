<script src="https://app.lemonsqueezy.com/js/lemon.js" defer></script>
@can('access premium features')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('subscriptionManager', () => ({
                LSUrl: '{{ route("billing-ls") }}',
                proceedBtn: '{{ __("Proceed to LemonSqueezy") }}',
                modalTitle: '{{ __("Manage your subscription") }}',
                subscriptionEnded: {{ empty($subEndsAt) ? 'false' : 'true' }},
                modalContent: 'manageSub',
                isLSLoginOpened: false,

                openModal() {
                    this.modalContent = 'manageSub';
                    this.modalTitle = '{{ __("Manage your subscription") }}';
                    this.proceedBtn = '{{ __("Proceed to LemonSqueezy") }}';
                    this.$dispatch('open-modal');
                },

                openModalForCancel() {
                    this.modalContent = 'cancelPlan';
                    this.modalTitle = '{{ __("Cancel Plan") }}';
                    this.proceedBtn = '{{ __("Cancel Plan") }}';
                    this.isLSLoginOpened = false;
                    this.$dispatch('open-modal');
                },

                openModalForRestore() {
                    this.modalContent = 'restorePlan';
                    this.modalTitle = '{{ __("Restore Plan") }}';
                    this.proceedBtn = '{{ __("Restore Plan") }}';
                    this.isLSLoginOpened = false;
                    this.$dispatch('open-modal');
                },

                closeModal() {
                    this.isOpen = false;
                },

                proceedButtonAction(noTab) {
                    if (noTab) {
                        window.location.replace('{{ route("billing-sp") }}');
                        return;
                    }
                    if (this.proceedBtn === '{{ __("Restore Plan") }}') {
                        this.restorePlan();
                        return;
                    } else if (this.proceedBtn === '{{ __("Cancel Plan") }}') {
                        this.cancelPlan();
                        return;
                    }
                    if (!this.isLSLoginOpened) {
                        this.openLemonSqueezyPortal();
                    } else {
                        this.closeModal();
                        this.showSpinner();
                    }
                },

                openLemonSqueezyPortal() {
                    // Open LemonSqueezy portal in a new tab
                    window.open(this.LSUrl, '_blank');

                    // Change the button text and hide the cancel button
                    this.proceedBtn = '{{ __("Close the window") }}';
                    this.isLSLoginOpened = true;

                    // Hide cancel button after proceeding
                    const cancelButton = document.querySelector('.cancel');
                    cancelButton.classList.add('hidden');
                },

                restorePlan() {
                    Livewire.dispatch('restorePlan');
                    this.closeModal();
                    this.showSpinner();
                },

                cancelPlan() {
                    Livewire.dispatch('cancelPlan');
                    this.closeModal();
                    this.showSpinner();
                },

                showSpinner() {
                    const elementIds = ['subInfos', 'subHistory', 'appLogo', 'buttonsManageSub'];
                    createSpinner({
                        spinnerText: '{{ __("Processing your request") }}',
                        textColor: '{{ $spinnerTextColor }}',
                        spinnerColor: '{{ $spinnerColor }}',
                        longWaitText: '{{ __("This is taking longer than expected. Please wait") }}',
                    });
                    hideElementsAndShowSpinner({
                        idsToHide: elementIds
                    });
                    setTimeout(() => {
                        window.location.replace('{{ route("dashboard") }}');
                    }, 2000);
                }
            }));
        });
    </script>
@endcan
