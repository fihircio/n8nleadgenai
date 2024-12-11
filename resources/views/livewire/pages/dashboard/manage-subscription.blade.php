<div>
    @if ($billingProvider === 'stripe')
        @include('livewire.pages.dashboard.partials.stripe-partials.stripe-manage-subscription')
    @elseif ($billingProvider === 'paddle')
        @include('livewire.pages.dashboard.partials.paddle-partials.paddle-manage-subscription')
    @elseif ($billingProvider === 'lemonsqueezy')
        @include('livewire.pages.dashboard.partials.lemonsqueezy-partials.lemonsqueezy-manage-subscription')
    @elseif ($billingProvider === 'nowpayments')
        @include('livewire.pages.dashboard.partials.nowpayments-partials.nowpayments-manage-subscription')
    @endif
</div>
