@php
    use App\Models\SubscriptionPlan;
    $user = Auth::user();
    $plans = SubscriptionPlan::where('billing_provider', Auth::user()?->billing_provider ?? config('saashovel.BILLING_PROVIDER'))->get()->keyBy('tier')->toArray();
    $subTier = \App\Models\SubscriptionPlan::where('plan_id', $user->subscriptionsNP()->latest()->first()?->nowpayments_plan)->first()?->name;
@endphp

@can('access premium features')
    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize Alpine.js component for subscription management
            Alpine.data('subscriptionManager', () => ({
                selectedTier: null,
                selectedPriceID: null,
                plans: @json($plans),

                openModal(type) {
                    const modalData = this.getModalData(type);
                    this.$dispatch('open-modal', {
                        ...modalData,
                        content: () => this.renderModalContent(modalData.contentTemplate)
                    });
                },

                getModalData(type) {
                    if (type === 'cancel') {
                        return this.getCancelModalData();
                    } else if (type === 'change') {
                        return this.getChangeModalData();
                    } else if (type === 'restore') {
                        return this.getRestoreModalData();
                    }
                },

                getCancelModalData() {
                    const endPlan = '{{ \Carbon\Carbon::parse($user->subscriptionNP()->ends_at)->translatedFormat('j F Y') }}';
                    return {
                        title: '{{ __("Cancel Plan") }}',
                        contentTemplate: `
                            <p class="mt-1 font-bold">{{ __("Are you sure you want to cancel your plan?") }}</p>
                            <p class="mt-1">{{ __("Your plan will expire on ") }}<span class="font-bold">${endPlan}</span>.</p>
                            <p class="mt-1">{{ __("If you change your mind you can restore your plan anytime before ") }}<span class="font-bold">${endPlan}</span>{{ __(' by clicking the "Restore Plan" button.') }}</p>
                        `,
                        confirmButtonText: '{{ __("Yes, cancel my subscription") }}',
                        confirmAction: () => this.handleCancelPlan()
                    };
                },

                getChangeModalData() {
                    return {
                        title: '{{ __("Change Plan") }}',
                        contentTemplate: `
                            <p class="my-3">{{ __(" Current plan: ") }}<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-black-800 dark:bg-teal-800/30 dark:text-teal-500">{{ $subTier }}</span></p>
                            <p>{{ __("Changing your subscription requires canceling your current plan. You can then choose and subscribe to a new plan.") }}</p>
                        `,
                        confirmButtonText: '{{ __("Cancel Plan Now") }}',
                        confirmAction: () => this.handleChangePlan()
                    };
                },

                getRestoreModalData() {
                    const endPlan = '{{ \Carbon\Carbon::parse($user->subscriptionNP()->ends_at)->translatedFormat('j F Y') }}';
                    return {
                        title: '{{ __("Restore Plan") }}',
                        contentTemplate: `{{ __("Restore your plan? Your billing cycle will continue and your subscription will renew on ") }}${endPlan}.`,
                        confirmButtonText: '{{ __("Restore Plan") }}',
                        confirmAction: () => this.handleRestorePlan()
                    };
                },

                renderModalContent(template) {
                    return `${template}`;
                },

                handleCancelPlan() {
                    Livewire.dispatch('cancelPlan');
                    this.showSpinner();
                },

                handleChangePlan() {
                    if (confirm('{{ __("Are you sure you want to proceed?") }}')) {
                        Livewire.dispatch('changePlan');
                        this.showSpinner();
                    }
                },

                handleRestorePlan() {
                    Livewire.dispatch('restorePlan');
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
                }
            }));

            // Initialize Livewire and page refresh handler
            Livewire.on('refreshThePage', () => {
                setTimeout(() => {
                    window.location.replace('{{ route("dashboard") }}');
                }, 2000);
            });
        });
    </script>
@endcan

@can('access basic features')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('subscriptionTable', () => ({
                planId: null,
                isRedirecting: false,
                planClicked: '',

                openModal(id, event) {
                    this.planClicked = event.target.parentElement.parentElement.parentElement.querySelector('h3').textContent;
                    this.planId = id;
                    this.$dispatch('open-modal');
                },

                closeModal() {
                    this.isOpen = false;
                },

                proceedWithPayment() {
                    if (this.planId) {
                        this.sendBillingRequest(this.planId);
                        this.startPolling();
                        this.closeModal();

                        // Show the spinner
                        createSpinner({
                            spinnerText: '{!! __("Awaiting <b>NOWPayments</b> confirmation") !!}',
                            textColor: '{{ $spinnerTextColor }}',
                            spinnerColor: '{{ $spinnerColor }}',
                            longWaitText: '{{ __("This is taking longer than expected. Please wait") }}',
                            insertTarget: '#showSpinner'
                        });

                        hideElementsAndShowSpinner({
                            idsToHide: ['subInfos', 'subHistory', 'appLogo', 'buttonsManageSub', 'pricingTitle', 'pricing']
                        });
                    }
                },

                sendBillingRequest(planId) {
                    fetch(`/billing-np/${planId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    }).catch(error => {
                        console.error('Error during billing request:', error);
                    });
                },

                startPolling() {
                    const userId = '{{ auth()->user()->id }}';
                    this.isRedirecting = false;
                    const pollInterval = setInterval(() => {
                        fetch(`/pollStatus/${userId}?np=true`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'active' && !this.isRedirecting) {
                                this.isRedirecting = true;
                                clearInterval(pollInterval);

                                const messageElement = document.getElementById("spinnerMessage");
                                messageElement.innerHTML = "";
                                const newText = "{{ __('Subscription confirmed! Refreshing...') }}";
                                messageElement.textContent = newText;

                                setTimeout(() => {
                                    window.location.replace('{{ route("dashboard") }}');
                                }, 1500);
                            }
                        })
                        .catch(error => {
                            console.error('Error during polling:', error);
                        });
                    }, 3000);
                }
            }));
        });
    </script>
@endcan
