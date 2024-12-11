@props([
    'spinnerColor' => theme('global', 'spinnerColor'),
    'spinnerTextColor' => theme('global', 'spinnerTextColor'),
])

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @php
        if (isset($message)) {
            $message = ',<br>' . $message;
        } else {
            $message = '';
        }

        if ($billingProvider === 'stripe') {
            $text = __('Awaiting <b>Stripe</b> confirmation');
        } elseif ($billingProvider === 'paddle') {
            $text = __('Awaiting <b>Paddle</b> confirmation');
        } elseif ($billingProvider === 'lemonsqueezy') {
            $text = __('Awaiting <b>Lemon Squeezy</b> confirmation');
        } elseif ($billingProvider === 'nowpayments') {
            $text = __('Awaiting <b>NOWPayments</b> confirmation');
        }
    @endphp
    <div id="showSpinner" class="p-6 lg:p-8 bg-white border-b border-gray-200"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let additionalMessage = '{!! $message !!}';
            createSpinner({
                spinnerText: '{!! $text !!}' + additionalMessage,
                textColor: '{{ $spinnerTextColor }}',
                spinnerColor: '{{ $spinnerColor }}',
                longWaitText: '{{ __("This is taking longer than expected. Please wait") }}',
                insertTarget: '#showSpinner'
            });
            hideElementsAndShowSpinner({
                idsToHide: [],
                timeout: 0
            });
            let isRedirecting = false; // Flag to track redirection status
            setInterval(() => {
                fetch('/pollStatus/{{ auth()->user()->id }}{{ $billingProvider === "nowpayments" ? "?np=true" : "" }}', {
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
                    return response.json(); // Assuming the API returns JSON data
                })
                .then(data => {
                    if (data.status === 'active' && !isRedirecting) {
                        isRedirecting = true; // Set the flag to true to prevent further redirections
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
            }, 3500);
        });
    </script>
</div>
