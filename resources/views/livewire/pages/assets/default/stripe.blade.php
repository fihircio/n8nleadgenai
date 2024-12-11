@can('access premium features')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('subscriptionManager', () => ({
                stripeUrl: '{{ route("billing-sp") }}',
                proceedBtn: '{{ __("Proceed to Stripe") }}',
                modalTitle: '{{ __("Manage your subscription") }}',
                modalContent: 'manageSub',
                isStripeLoginOpened: false,

                // Open the modal
                openModal() {
                    this.modalContent = 'manageSub';
                    this.modalTitle = '{{ __("Manage your subscription") }}';
                    this.proceedBtn = '{{ __("Proceed to Stripe") }}';
                    this.$dispatch('open-modal');
                },

                openModalForCancel() {
                    this.modalContent = 'cancelPlan';
                    this.modalTitle = '{{ __("Cancel Plan") }}';
                    this.proceedBtn = '{{ __("Cancel Plan") }}';
                    this.isStripeLoginOpened = false;
                    this.$dispatch('open-modal');
                },

                openModalForRestore() {
                    this.modalContent = 'restorePlan';
                    this.modalTitle = '{{ __("Restore Plan") }}';
                    this.proceedBtn = '{{ __("Restore Plan") }}';
                    this.isStripeLoginOpened = false;
                    this.$dispatch('open-modal');
                },

                // Close the modal
                closeModal() {
                    this.isOpen = false;
                },

                // Handle the proceed button logic
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
                    if (!this.isStripeLoginOpened) {
                        this.openStripePortal();
                    } else {
                        this.closeModal();
                        this.showSpinner();
                    }
                },

                openStripePortal() {
                    // Open Stripe portal in a new window/tab
                    window.open(this.stripeUrl, '_blank');

                    // Change button text and state after opening Stripe
                    this.proceedBtn = '{{ __("Close the window") }}';
                    this.isStripeLoginOpened = true;

                    // Hide cancel button after proceeding
                    const cancelButton = document.querySelector('.cancel');
                    if (cancelButton) cancelButton.classList.add('hidden');
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

                // Show a loading spinner and redirect after a delay
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
