<div x-data="subscriptionManager">
    <!-- Modal structure -->
    <x-custom-modal id="manage-subscription" max-width="lg" />
    <!-- Buttons to open the modal -->
    <div id="buttonsManageSub" class="flex flex-col sm:flex-row gap-3">
        <button id="restorePlan" @click="openModal('restore')" class="{{ when(empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Restore Plan') }}
        </button>
        @if ($subStatus !== 'trialing')
            <button id="changePlan" @click="openModal('change')" class="{{ when(!empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                {{ __('Change Plan') }}
            </button>
            <a  href="{{ auth()->user()->subscription()->asPaddleSubscription()['management_urls']['update_payment_method'] }}"
                type="button"
                x-cloak
                class="{{ when(!empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:pointer-events-none"
                x-data="{ clicked: false }"
                @click.prevent="if (!clicked) { clicked = true; $nextTick(() => { window.location.href = $el.href; }) }"
                :class="{ 'pointer-events-none': clicked }"
                :aria-disabled="clicked">
                {{ __('Update payment method') }}
                <span
                    class="ml-2 animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-white rounded-full"
                    :class="{ 'hidden': !clicked }">
                </span>
            </a>
        @endif
        <button id="cancelPlan" @click="openModal('cancel')" class="{{ when(!empty($subEndsAt), 'hidden') }} py-3 px-4 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none">
            {{ __('Cancel Plan') }}
        </button>
    </div>
</div>
