{{-- IMPORTANT: Do not delete or modify the code below this line.
     This section contains essential code for managing subscriptions in the app.
     If removed, the subscription functionality will not work properly. --}}

<script type="text/javascript">
    document.addEventListener('livewire:init', () => {
        Livewire.on('refreshThePage', (event) => {
            setTimeout(() => {
                window.location.replace('{{ route("dashboard") }}');
            }, 2000);
        });
    });
</script>

@if (auth()->user()?->isSocialite())
    <script>
        function triggerEscKeyPress() {
            const event = new KeyboardEvent('keydown', {
                key: 'Escape',
                code: 'Escape',
                which: 27,
                keyCode: 27,
                bubbles: true,
                cancelable: true
            });

            document.dispatchEvent(event);
        }
        var deleteButton = document.getElementById('deleteUser');
        if (deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                event.preventDefault();
                triggerEscKeyPress();
                fetch('{{ route("deleteUserSocialite") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message === 'USER_DELETED') {
                        window.location.replace('{{ route("home") }}');
                    }
                })
            });
        }
    </script>
@endif

@if (Auth::user()?->billing_provider === 'stripe')
    @include('livewire.pages.assets.default.stripe')
@elseif (Auth::user()?->billing_provider === 'paddle')
    @include('livewire.pages.assets.default.paddle')
@elseif (Auth::user()?->billing_provider === 'lemonsqueezy')
    @include('livewire.pages.assets.default.lemonsqueezy')
@elseif (Auth::user()?->billing_provider === 'nowpayments')
    @include('livewire.pages.assets.default.nowpayments')
@endif

{{-- IMPORTANT: Do not delete or modify the code above this line.
     This section contains essential code for managing subscriptions in the app.
     If removed, the subscription functionality will not work properly. --}}
