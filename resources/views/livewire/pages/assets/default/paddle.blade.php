<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
@can('access premium features')
    @php
        $plans = \App\Models\SubscriptionPlan::where('billing_provider', Auth::user()?->billing_provider ?? config('saashovel.BILLING_PROVIDER'))->get()->keyBy('tier')->toArray();
        $subTier = \App\Models\SubscriptionPlan::where('plan_id', Auth::user()->subscriptions()->latest()->first()->items->first()->price_id)->first()->name;
    @endphp
    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize Alpine.js component for subscription management
            Alpine.data('subscriptionManager', () => ({
                subTier: '{{ $subTier }}',
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
                    const endPlan = '{{ \Carbon\Carbon::parse(auth()->user()->subscription()->asPaddleSubscription()['current_billing_period']['ends_at'])->translatedFormat('j F Y') }}';
                    return {
                        title: '{{ __("Cancel Plan") }}',
                        contentTemplate: `
                            <p class="mt-1 font-bold">{{ __("Are you sure you want to cancel your plan?") }}</p>
                            <p class="mt-1">{{ __("If so, your plan will remain active until ") }}<span class="font-bold">${endPlan}</span>{{ __(", then it will be canceled and you won't be charged again.") }}</p>
                            <p class="mt-1">{{ __("If you change your mind you can always restore your plan anytime before ") }}<span class="font-bold">${endPlan}</span>{{ __(' by clicking the "Restore Plan" button.') }}</p>
                        `,
                        confirmButtonText: '{{ __("Yes, cancel my subscription") }}',
                        confirmAction: () => this.handleCancelPlan()
                    };
                },

                getChangeModalData() {
                    return {
                        title: '{{ __("Change Plan") }}',
                        contentTemplate: `
                            <p>{{ __("Choose a new plan that suits your needs. You can upgrade, downgrade, or switch to a different plan.") }}</p>
                            <p class="my-3">{{ __(" Current plan: ") }}<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-black-800 dark:bg-teal-800/30 dark:text-teal-500" x-text="subTier"></span></p>
                            <h2>{{ __("Select new plan:") }}</h2>
                            <template x-for="tier in getAlternativeSubscriptionTiers()" :key="tier.name">
                                <div
                                    @click="selectTier(tier.name, tier.plan_id)"
                                    :class="{'bg-blue-200': selectedTier === tier.name}"
                                    class="p-3 my-3 border rounded-md cursor-pointer hover:bg-blue-100"
                                >
                                    <span x-text="tier.name"></span>,
                                    {{ __("Price: ") }}<span x-text="tier.price / 100"></span>
                                    <span x-text="tier.currency"></span>{{ __(" per month") }}
                                </div>
                            </template>
                        `,
                        confirmButtonText: '{{ __("Update Plan") }}',
                        confirmAction: () => this.handleChangePlan()
                    };
                },

                getRestoreModalData() {
                    const endPlan = '{{ \Carbon\Carbon::parse(auth()->user()->subscription()->asPaddleSubscription()['current_billing_period']['ends_at'])->translatedFormat('j F Y') }}';
                    return {
                        title: '{{ __("Restore Plan") }}',
                        contentTemplate: `{{ __("Restore your plan? If so, your current billing cycle will continue and your subscription will automatically renew on ") }}${endPlan}.`,
                        confirmButtonText: '{{ __("Restore Plan") }}',
                        confirmAction: () => this.handleRestorePlan()
                    };
                },

                renderModalContent(template) {
                    return `${template}`;
                },

                getAlternativeSubscriptionTiers() {
                    return Object.values(this.plans).filter(tier => tier.name !== this.subTier);
                },

                selectTier(tierName, priceId) {
                    this.selectedTier = this.selectedTier === tierName ? null : tierName;
                    this.selectedPriceID = this.selectedTier ? priceId : null;
                },

                handleCancelPlan() {
                    Livewire.dispatch('cancelPlan');
                    this.showSpinner();
                },

                handleChangePlan() {
                    if (this.selectedPriceID) {
                        if (confirm('{{ __("Are you sure you want to proceed?") }}')) {
                            Livewire.dispatch('changePlan', { priceId: this.selectedPriceID });
                            this.showSpinner();
                            return true; // Signal that the modal can close
                        } else {
                            // User canceled the confirmation prompt
                            return false; // Keep the modal open
                        }
                    } else {
                        alert('{{ __("Please, select a plan.") }}');
                        return false; // Keep the modal open
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

            // Initialize Livewire and Paddle within the Alpine.js initialization
            Livewire.on('refreshThePage', (event) => {
                setTimeout(() => {
                    window.location.replace('{{ route("dashboard") }}');
                }, 2000);
            });

            // Configure Paddle.js
            Paddle.Environment.set("sandbox");
            Paddle.Initialize({
                token: "{{ config('saashovel.PADDLE_CLIENT_SIDE_TOKEN') }}", // replace with a client-side token
                // eventCallback: handleCheckoutClosed // Uncomment and define if needed
            });
        });
    </script>
@endcan

@can('access basic features')
    @php $plans = \App\Models\SubscriptionPlan::where('billing_provider', Auth::user()?->billing_provider ?? config('saashovel.BILLING_PROVIDER'))->get()->keyBy('tier')->toArray(); @endphp
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('subscriptionTable', () => ({
                plans: @json($plans),
                customerInfo: {},
                isRedirecting: false,
                pollInterval: null,
                openCheckout(priceId) {
                    // Define customer info
                    this.customerInfo = {
                        email: "{{ auth()->user()->email }}"
                    };
                    // Initialize Paddle
                    Paddle.Environment.set("sandbox");
                    Paddle.Initialize({
                        token: "{{ config('saashovel.PADDLE_CLIENT_SIDE_TOKEN') }}",
                        eventCallback: (event) => this.handleCheckoutClosed(event)
                    });
                    Paddle.Checkout.open({
                        items: [{
                            priceId: priceId,
                            quantity: 1
                        }],
                        customer: this.customerInfo
                    });
                },

                handleSubscribeClick(priceId) {
                    if (priceId) {
                        this.openCheckout(priceId);
                    } else {
                        alert('{{ __("Invalid plan selected.") }}');
                    }
                },

                handleCheckoutClosed(event) {
                    if (event.name === 'checkout.closed' && event.data.status === 'completed') {
                        const elementIds = ['pricing', 'subInfos', 'pricingTitle', 'appLogo'];
                        createSpinner({
                            spinnerText: '{!! __("Awaiting <b>Paddle</b> confirmation") !!}',
                            textColor: '{{ $spinnerTextColor }}',
                            spinnerColor: '{{ $spinnerColor }}',
                            longWaitText: '{{ __("This is taking longer than expected. Please wait") }}',
                            insertTarget: '#showSpinner'
                        });
                        hideElementsAndShowSpinner({
                            idsToHide: elementIds
                        });
                        this.isRedirecting = false; // Flag to track redirection status
                        this.pollInterval = setInterval(() => {
                            fetch('/pollStatus/{{ auth()->user()->id }}', {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if ((data.status === 'active' || data.status === 'trialing') && !this.isRedirecting) {
                                    this.isRedirecting = true; // Set the flag to true to prevent further redirections
                                    clearInterval(this.pollInterval); // Stop polling
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
                                // Handle errors gracefully
                                console.error('Error fetching data:', error);
                            });
                        }, 3000);
                    }
                },
            }));
        });
    </script>
@endcan



